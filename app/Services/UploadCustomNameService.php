<?php

namespace App\Services;

use File;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Google\Cloud\Storage\StorageClient as StorageClients;

class UploadCustomNameService
{
    /**
     * Upload image.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function uploadFile($path, $custom_name, $var_name, $request, $data = null, $set = null)
    {
        if ($request) {
            // File and name setup
            $file = $request;
            $fileName = str_replace(' ', '-', $custom_name) . '.' . $file->getClientOriginalExtension();
            $s3FilePath = $path . $fileName;

            // S3 client setup
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
                // Upload to S3 without ACL
                $s3->putObject([
                    'Bucket' => $bucket,
                    'Key' => $s3FilePath,
                    'Body' => fopen($file->getPathname(), 'r'),
                    'ContentType' => $file->getMimeType(),
                ]);

                $fileUploaded = $s3FilePath;

            } catch (AwsException $e) {
                Log::error('S3 Upload Error: ' . $e->getMessage());
            }
        } else {
            if ($set === 'update') {
                $fileUploaded = isset($data) ? @$data->$var_name : null;
            } else {
                $fileUploaded = null;
            }
        }

        return $fileUploaded;
    }

    // public function uploadFile($path, $custom_name, $var_name, $request, $data = null, $set = null)
    // {
    //     if (@$request) {
    //         // upload image
    //         $file = $request;
    //         $fileName = str_replace(' ', '-', $custom_name) . '.' . $file->getClientOriginalExtension();
    //         // Storage::put($path . $fileName, File::get($file));
    //         // $fileUploaded = $path . $fileName;

    //         // google cloud storage
    //         $googleConfigFile = file_get_contents(config_path('googlecloud.json'));
    //         $storage = new StorageClients([
    //             'keyFile' => json_decode($googleConfigFile, true)
    //         ]);
    //         $storageBucketName = config('googlecloud.storage_bucket');
    //         $bucket = $storage->bucket($storageBucketName);
    //         $fileSource = fopen($file, 'r');
    //         $newFolderName = $var_name . '_' . date("Y-m-d") . '_' . date("H:i:s");
    //         $googleCloudStoragePath = $path . $fileName;
    //         /* Upload a file to the bucket.
    //         Using Predefined ACLs to manage object permissions, you may
    //         upload a file and give read access to anyone with the URL.*/
    //         $bucket->upload($fileSource, [
    //             'predefinedAcl' => 'publicRead',
    //             'name' => $googleCloudStoragePath
    //         ]);

    //         $fileUploaded = $googleCloudStoragePath;
    //     } else {
    //         if ($set == 'update') {
    //             $fileUploaded = isset($data) ? @$data->$var_name : null;
    //         } else {
    //             $fileUploaded = null;
    //         }
    //     }

    //     return $fileUploaded;
    // }
}
