const fs = require('fs');
const path = require('path');
let cloudinary;
try {
  cloudinary = require('cloudinary').v2;
} catch (err) {
  cloudinary = require('E:/cloudinary-images-VMS/node_modules/cloudinary').v2;
}

const cloudName = process.env.CLOUDINARY_CLOUD_NAME;
const apiKey = process.env.CLOUDINARY_API_KEY;
const apiSecret = process.env.CLOUDINARY_API_SECRET;

if (!cloudName || !apiKey || !apiSecret) {
  console.error('Missing Cloudinary credentials in environment variables.');
  process.exit(1);
}

cloudinary.config({
  cloud_name: cloudName,
  api_key: apiKey,
  api_secret: apiSecret,
  secure: true,
});

const projectRoot = path.resolve(__dirname, '..');
const imagesDir = path.join(projectRoot, 'storage', 'app', 'public', 'Images');

if (!fs.existsSync(imagesDir)) {
  console.error(`Images directory not found: ${imagesDir}`);
  process.exit(1);
}

const files = fs
  .readdirSync(imagesDir)
  .filter((name) => name.toLowerCase().endsWith('.png'))
  .sort();

function wait(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

async function uploadWithRetry(filePath, publicId, maxAttempts = 3) {
  let lastError = null;

  for (let attempt = 1; attempt <= maxAttempts; attempt++) {
    try {
      const result = await new Promise((resolve, reject) => {
        const timer = setTimeout(() => {
          reject(new Error('Upload timed out after 90s'));
        }, 90000);

        cloudinary.uploader.upload(
          filePath,
          {
            public_id: publicId,
            overwrite: true,
            resource_type: 'image',
            folder: 'vms',
          },
          (error, response) => {
            clearTimeout(timer);
            if (error) {
              reject(error);
              return;
            }
            resolve(response);
          }
        );
      });
      return result;
    } catch (error) {
      lastError = error;
      if (attempt < maxAttempts) {
        await wait(1000 * attempt);
      }
    }
  }

  throw lastError;
}

async function main() {
  console.log(`Total PNG files found: ${files.length}`);

  let uploaded = 0;
  let failed = 0;

  for (let i = 0; i < files.length; i++) {
    const fileName = files[i];
    const filePath = path.join(imagesDir, fileName);
    const baseName = path.parse(fileName).name;
    const processed = i + 1;

    if (processed <= 3) {
      console.log(`Starting ${processed}: ${fileName}`);
    }

    try {
      await uploadWithRetry(filePath, baseName);
      uploaded++;
    } catch (error) {
      failed++;
      console.error(`Failed: ${fileName} -> ${error.message}`);
    }

    if (processed % 25 === 0 || processed === files.length) {
      console.log(`Processed ${processed}/${files.length} | Uploaded ${uploaded} | Failed ${failed}`);
    }
  }

  console.log(`Done. Uploaded ${uploaded}, Failed ${failed}, Total ${files.length}`);
  process.exit(failed > 0 ? 2 : 0);
}

main().catch((error) => {
  console.error('Uploader crashed:', error.message);
  process.exit(1);
});
