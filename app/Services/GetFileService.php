<?php

namespace App\Services;

use Carbon\Carbon;
// use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Google\Cloud\Storage\StorageClient;

class GetFileService
{

    public function getFile($filePath)
    {
        try {
            return Storage::disk('s3')->temporaryUrl(
                $filePath,
                Carbon::now()->addHour()
            );
        } catch (\Exception $e) {
            return null;
        }
    }

    // public function getFile($filePath)
    // {
    //     $googleConfigFile = file_get_contents(config_path('googlecloud.json'));
    //     $storage = new StorageClient([
    //         'keyFile' => json_decode($googleConfigFile, true)
    //     ]);

    //     $storageBucketName = config('googlecloud.storage_bucket');
    //     $bucket = $storage->bucket($storageBucketName);

    //     $object = $bucket->object($filePath);
    //     # This URL is valid for 1 hour
    //     $url = $object->signedUrl(new \DateTime('next hour'));

    //     return $url;
    // }
}
