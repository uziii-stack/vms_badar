<?php

declare(strict_types=1);

use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$projectRoot = realpath(__DIR__ . '/..');
if ($projectRoot === false) {
    fwrite(STDERR, "Project root not found.\n");
    exit(1);
}

Dotenv::createImmutable($projectRoot)->safeLoad();

$cloudName = $_ENV['CLOUDINARY_CLOUD_NAME'] ?? getenv('CLOUDINARY_CLOUD_NAME') ?: '';
$apiKey = $_ENV['CLOUDINARY_API_KEY'] ?? getenv('CLOUDINARY_API_KEY') ?: '';
$apiSecret = $_ENV['CLOUDINARY_API_SECRET'] ?? getenv('CLOUDINARY_API_SECRET') ?: '';

if ($cloudName === '' || $apiKey === '' || $apiSecret === '') {
    fwrite(STDERR, "Cloudinary credentials are missing in .env.\n");
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

try {
    $path = $projectRoot . '/storage/app/public/Images/00125867-54c0-4c64-bbdc-26458fb57eb8.png';
    $res = (new UploadApi())->upload($path, [
        'public_id' => 'vms/probe-single',
        'overwrite' => true,
        'resource_type' => 'image',
    ]);

    echo "ok\n";
    echo ($res['public_id'] ?? '') . "\n";
    echo ($res['secure_url'] ?? '') . "\n";
    exit(0);
} catch (Throwable $e) {
    echo "failed\n";
    echo $e->getMessage() . "\n";
    exit(2);
}
