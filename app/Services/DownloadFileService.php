<?php
namespace App\Services;

use Aws\S3\S3Client;

use Aws\Exception\AwsException;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Response;

class DownloadFileService
{

    function downloadFile($filePath)
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
            $result = $s3->getObject([
                'Bucket' => $bucket,
                'Key' => $filePath,
            ]);

            $headers = [
                'Content-Type' => $result['ContentType'],
                'Content-Disposition' => 'attachment; filename="' . basename($filePath) . '"',
            ];

            return response()->stream(
                function () use ($result) {
                    echo $result['Body'];
                },
                200,
                $headers
            );
        } catch (AwsException $e) {
            abort(404);
        }
        }


    # gcp
    // public function downloadFile($filePath)
    // {
    //     $googleConfigFile = file_get_contents(config_path('googlecloud.json'));
    //     $storage = new StorageClient([
    //         'keyFile' => json_decode($googleConfigFile, true)
    //     ]);

    //     $storageBucketName = config('googlecloud.storage_bucket');
    //     $bucket = $storage->bucket($storageBucketName);

    //     $object = $bucket->object($filePath);

    //     if (!$object->exists()) {
    //         abort(404);
    //     }

    //     // Stream the file to the user
    //     $fileContents = $object->downloadAsStream();
    //     $headers = [
    //         'Content-Type' => $object->info()['contentType'],
    //         'Content-Disposition' => 'attachment; filename="' . $object->name() . '"',
    //     ];

    //     return Response::stream(
    //         function () use ($fileContents) {
    //             echo $fileContents->getContents();
    //         },
    //         200,
    //         $headers
    //     );
    // }
}
