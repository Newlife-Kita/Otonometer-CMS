<?php

namespace App\Services;

use Carbon\Carbon;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Google\Cloud\Storage\StorageClient;

class GetFileService
{

    public function getFile($filePath)
    {
        $s3 = new S3Client([
            'region' => config('filesystems.disks.s3.region'),
            'version' => 'latest',
            'credentials' => [
                'key' => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret'),
            ],
        ]);

        $bucket = config('filesystems.disks.s3.bucket');

        try {
            $cmd = $s3->getCommand('GetObject', [
                'Bucket' => $bucket,
                'Key' => $filePath,
            ]);

            $request = $s3->createPresignedRequest($cmd, Carbon::now()->addHour());

            return (string) $request->getUri();

        } catch (AwsException $e) {
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
