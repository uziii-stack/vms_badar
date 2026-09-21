<?php

declare(strict_types=1);

use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

@ini_set('max_execution_time', '0');
@set_time_limit(0);

$projectRoot = realpath(__DIR__ . '/..');
if ($projectRoot === false) {
    fwrite(STDERR, "Unable to resolve project root.\n");
    exit(1);
}

$imagesDir = $projectRoot . '/storage/app/public/Images';
$csvPath = $imagesDir . '/cloudinary_upload_results.csv';

if (!is_dir($imagesDir)) {
    fwrite(STDERR, "Images directory not found: {$imagesDir}\n");
    exit(1);
}

if (!file_exists($csvPath)) {
    fwrite(STDERR, "CSV file not found: {$csvPath}\n");
    exit(1);
}

$dotenv = Dotenv::createImmutable($projectRoot);
$dotenv->safeLoad();

$cloudName = $_ENV['CLOUDINARY_CLOUD_NAME']
    ?? $_SERVER['CLOUDINARY_CLOUD_NAME']
    ?? getenv('CLOUDINARY_CLOUD_NAME')
    ?? '';
$apiKey = $_ENV['CLOUDINARY_API_KEY']
    ?? $_SERVER['CLOUDINARY_API_KEY']
    ?? getenv('CLOUDINARY_API_KEY')
    ?? '';
$apiSecret = $_ENV['CLOUDINARY_API_SECRET']
    ?? $_SERVER['CLOUDINARY_API_SECRET']
    ?? getenv('CLOUDINARY_API_SECRET')
    ?? '';

if ($cloudName === '' || $apiKey === '' || $apiSecret === '') {
    fwrite(STDERR, "Cloudinary credentials are missing in environment.\n");
    exit(1);
}

Configuration::instance([
    'cloud' => [
        'cloud_name' => $cloudName,
        'api_key' => $apiKey,
        'api_secret' => $apiSecret,
    ],
    'url' => [
        'secure' => true,
    ],
]);

$rowsByFile = [];
$fh = fopen($csvPath, 'rb');
if ($fh === false) {
    fwrite(STDERR, "Unable to read CSV: {$csvPath}\n");
    exit(1);
}

$header = fgetcsv($fh);
if ($header === false) {
    fclose($fh);
    fwrite(STDERR, "CSV is empty: {$csvPath}\n");
    exit(1);
}

while (($data = fgetcsv($fh)) !== false) {
    $row = array_combine($header, $data);
    if (!is_array($row) || !isset($row['file'])) {
        continue;
    }
    $rowsByFile[$row['file']] = [
        'file' => (string)($row['file'] ?? ''),
        'public_id' => (string)($row['public_id'] ?? ''),
        'secure_url' => (string)($row['secure_url'] ?? ''),
        'status' => (string)($row['status'] ?? ''),
        'error' => (string)($row['error'] ?? ''),
    ];
}
fclose($fh);

$imageFiles = glob($imagesDir . '/*.png') ?: [];
sort($imageFiles);

$uploader = new UploadApi();

$processed = 0;
$uploaded = 0;
$alreadyOk = 0;
$failed = 0;

foreach ($imageFiles as $filePath) {
    $processed++;
    $fileName = basename($filePath);
    $baseName = pathinfo($fileName, PATHINFO_FILENAME);

    if (isset($rowsByFile[$fileName]) && strtolower($rowsByFile[$fileName]['status']) === 'ok') {
        $alreadyOk++;
        if ($processed % 200 === 0) {
            echo "Processed {$processed} | Uploaded {$uploaded} | Failed {$failed} | Already OK {$alreadyOk}\n";
        }
        continue;
    }

    try {
        $result = $uploader->upload($filePath, [
            'public_id' => 'vms/' . $baseName,
            'overwrite' => true,
            'resource_type' => 'image',
        ]);

        $rowsByFile[$fileName] = [
            'file' => $fileName,
            'public_id' => (string)($result['public_id'] ?? ('vms/' . $baseName)),
            'secure_url' => (string)($result['secure_url'] ?? ''),
            'status' => 'ok',
            'error' => '',
        ];
        $uploaded++;
    } catch (Throwable $e) {
        $rowsByFile[$fileName] = [
            'file' => $fileName,
            'public_id' => 'vms/' . $baseName,
            'secure_url' => '',
            'status' => 'failed',
            'error' => $e->getMessage(),
        ];
        $failed++;
    }

    if ($processed % 50 === 0) {
        echo "Processed {$processed} | Uploaded {$uploaded} | Failed {$failed} | Already OK {$alreadyOk}\n";
    }
}

ksort($rowsByFile);

$writeHandle = fopen($csvPath, 'wb');
if ($writeHandle === false) {
    fwrite(STDERR, "Unable to write CSV: {$csvPath}\n");
    exit(1);
}

fputcsv($writeHandle, ['file', 'public_id', 'secure_url', 'status', 'error']);
foreach ($rowsByFile as $row) {
    fputcsv($writeHandle, [
        $row['file'],
        $row['public_id'],
        $row['secure_url'],
        $row['status'],
        $row['error'],
    ]);
}
fclose($writeHandle);

echo "Done. Total files: " . count($imageFiles) . " | Uploaded now: {$uploaded} | Failed: {$failed} | Already OK: {$alreadyOk}\n";

exit($failed > 0 ? 2 : 0);
