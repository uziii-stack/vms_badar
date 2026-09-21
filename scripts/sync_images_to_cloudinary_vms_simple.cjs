const fs = require('fs');
const path = require('path');

let cloudinary;
try {
  cloudinary = require('cloudinary').v2;
} catch (err) {
  cloudinary = require('E:/cloudinary-images-VMS/node_modules/cloudinary').v2;
}

const CONFIG = {
  cloud_name: 'dbxbhlped',
  api_key: '443343356148214',
  api_secret: 'AfX4wFemJfq0ggut2XlKvI2Cw5o',
  folder: 'vms',
  concurrency: 6,
  retryCount: 3,
};

const projectRoot = path.resolve(__dirname, '..');
const imagesDir = path.join(projectRoot, 'storage', 'app', 'public', 'Images');
const uploadedDir = path.join(imagesDir, 'uploaded');
const csvPath = path.join(imagesDir, 'cloudinary_upload_results.csv');

function parseCsvLine(line) {
  const values = [];
  let cur = '';
  let inQuotes = false;

  for (let i = 0; i < line.length; i++) {
    const ch = line[i];
    if (ch === '"') {
      if (inQuotes && line[i + 1] === '"') {
        cur += '"';
        i++;
      } else {
        inQuotes = !inQuotes;
      }
    } else if (ch === ',' && !inQuotes) {
      values.push(cur);
      cur = '';
    } else {
      cur += ch;
    }
  }

  values.push(cur);
  return values;
}

function toCsvLine(values) {
  return values
    .map((value) => {
      const s = String(value ?? '');
      if (s.includes(',') || s.includes('"') || s.includes('\n') || s.includes('\r')) {
        return `"${s.replace(/"/g, '""')}"`;
      }
      return s;
    })
    .join(',');
}

function readCsvRows(filePath) {
  if (!fs.existsSync(filePath)) {
    return [];
  }

  const content = fs.readFileSync(filePath, 'utf8');
  if (!content.trim()) {
    return [];
  }

  const lines = content.replace(/^\uFEFF/, '').split(/\r?\n/).filter((l) => l.length > 0);
  if (lines.length === 0) {
    return [];
  }

  const header = parseCsvLine(lines[0]);
  const rows = [];

  for (let i = 1; i < lines.length; i++) {
    const vals = parseCsvLine(lines[i]);
    const row = {};
    for (let j = 0; j < header.length; j++) {
      row[header[j]] = vals[j] ?? '';
    }
    rows.push(row);
  }

  return rows;
}

function writeCsvRows(filePath, rows) {
  const header = ['file', 'public_id', 'secure_url', 'status', 'error'];
  const lines = [toCsvLine(header)];

  rows
    .sort((a, b) => String(a.file).localeCompare(String(b.file)))
    .forEach((r) => {
      lines.push(
        toCsvLine([
          r.file ?? '',
          r.public_id ?? '',
          r.secure_url ?? '',
          r.status ?? '',
          r.error ?? '',
        ])
      );
    });

  fs.writeFileSync(filePath, lines.join('\n') + '\n', 'utf8');
}

function ensureDir(dirPath) {
  if (!fs.existsSync(dirPath)) {
    fs.mkdirSync(dirPath, { recursive: true });
  }
}

function moveToUploadedIfExists(fileName) {
  const src = path.join(imagesDir, fileName);
  const dst = path.join(uploadedDir, fileName);

  if (!fs.existsSync(src)) {
    return false;
  }

  if (fs.existsSync(dst)) {
    fs.unlinkSync(src);
    return true;
  }

  fs.renameSync(src, dst);
  return true;
}

function listRemainingPngs() {
  return fs
    .readdirSync(imagesDir)
    .filter((name) => name.toLowerCase().endsWith('.png'))
    .sort();
}

async function uploadFile(fileName) {
  const filePath = path.join(imagesDir, fileName);
  const baseName = path.parse(fileName).name;

  let lastError;
  for (let attempt = 1; attempt <= CONFIG.retryCount; attempt++) {
    try {
      const res = await new Promise((resolve, reject) => {
        cloudinary.uploader.upload(
          filePath,
          {
            folder: CONFIG.folder,
            public_id: baseName,
            overwrite: true,
            resource_type: 'image',
          },
          (error, response) => {
            if (error) {
              reject(error);
              return;
            }
            resolve(response);
          }
        );
      });
      return res;
    } catch (err) {
      lastError = err;
    }
  }

  throw lastError;
}

async function main() {
  if (!fs.existsSync(imagesDir)) {
    throw new Error(`Images folder not found: ${imagesDir}`);
  }

  ensureDir(uploadedDir);

  cloudinary.config({
    cloud_name: CONFIG.cloud_name,
    api_key: CONFIG.api_key,
    api_secret: CONFIG.api_secret,
    secure: true,
  });

  const rows = readCsvRows(csvPath);
  const rowByFile = new Map(rows.map((r) => [r.file, r]));

  let movedFromCsv = 0;
  for (const row of rows) {
    if (String(row.status).toLowerCase() === 'ok') {
      if (moveToUploadedIfExists(row.file)) {
        movedFromCsv++;
      }
    }
  }

  const remaining = listRemainingPngs();
  let uploadedNow = 0;
  let failedNow = 0;

  console.log(`Moved already-uploaded files to uploaded folder: ${movedFromCsv}`);
  console.log(`Remaining files to upload: ${remaining.length}`);

  let nextIndex = 0;
  let processed = 0;

  async function worker() {
    while (true) {
      const i = nextIndex;
      nextIndex += 1;
      if (i >= remaining.length) {
        return;
      }

      const fileName = remaining[i];
      const baseName = path.parse(fileName).name;

      try {
        const result = await uploadFile(fileName);
        const row = rowByFile.get(fileName) || { file: fileName };
        row.public_id = result.public_id || `${CONFIG.folder}/${baseName}`;
        row.secure_url = result.secure_url || '';
        row.status = 'ok';
        row.error = '';
        rowByFile.set(fileName, row);

        moveToUploadedIfExists(fileName);
        uploadedNow++;
      } catch (err) {
        const row = rowByFile.get(fileName) || { file: fileName };
        row.public_id = `${CONFIG.folder}/${baseName}`;
        row.secure_url = '';
        row.status = 'failed';
        row.error = err && err.message ? err.message : String(err);
        rowByFile.set(fileName, row);
        failedNow++;
      }

      processed++;
      if (processed % 50 === 0 || processed === remaining.length) {
        writeCsvRows(csvPath, Array.from(rowByFile.values()));
        console.log(`Processed ${processed}/${remaining.length} | Uploaded ${uploadedNow} | Failed ${failedNow}`);
      }
    }
  }

  const workers = Array.from({ length: Math.min(CONFIG.concurrency, remaining.length) }, () => worker());
  await Promise.all(workers);

  writeCsvRows(csvPath, Array.from(rowByFile.values()));

  const finalRemaining = listRemainingPngs().length;
  const finalUploaded = fs
    .readdirSync(uploadedDir)
    .filter((name) => name.toLowerCase().endsWith('.png')).length;

  console.log('Done.');
  console.log(`Uploaded now: ${uploadedNow}`);
  console.log(`Failed now: ${failedNow}`);
  console.log(`Local uploaded folder count: ${finalUploaded}`);
  console.log(`Local remaining in Images root: ${finalRemaining}`);

  process.exit(failedNow > 0 ? 2 : 0);
}

main().catch((err) => {
  console.error(err.message || String(err));
  process.exit(1);
});
