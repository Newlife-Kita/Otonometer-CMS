<?php

namespace App\Services;

use File;
// use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Google\Cloud\Storage\StorageClient as StorageClients;

class UploadFileService
{
    /**
     * Upload image.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function uploadFile($path, $var_name, $request, $data = null, $set = null)
    {
        if ($request) {
            // Persiapan file
            $file = $request;
            $fileName = uniqid() . '.' . str_replace(' ', '_', $file->getClientOriginalName());
            $fullPath = $path . $fileName;

            try {
                // Upload ke MinIO
                Storage::disk('s3')->put(
                    $fullPath,
                    file_get_contents($file->getRealPath()),
                    [
                        'visibility' => 'private',
                        'ContentType' => $file->getMimeType(),
                    ]
                );

                $fileUploaded = $fullPath;
            } catch (\Exception $e) {
                Log::error('MinIO Upload Error: ' . $e->getMessage());
                $fileUploaded = null;
            }
        } else {
            // Mode update (jika tidak ada file baru)
            if ($set == 'update') {
                $fileUploaded = isset($data) ? @$data->$var_name : null;
            } else {
                $fileUploaded = null;
            }
        }

        return $fileUploaded;
    }
 # gcp
    // public function uploadFile($path, $var_name, $request, $data = null, $set = null)
    // {
    //     if (@$request) {
    //         // upload image
    //         $file = $request;
    //         $fileName = uniqid() . '.' . str_replace(' ', '_', $file->getClientOriginalName());
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
