<?php

namespace App\Services;

use Illuminate\Http\Request;
use File;
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
        if (@$request) {
            // upload image
            $file = $request;
            $fileName = uniqid() . '.' . str_replace(' ', '_', $file->getClientOriginalName());
            // Storage::put($path . $fileName, File::get($file));
            // $fileUploaded = $path . $fileName;

            // google cloud storage
            $googleConfigFile = file_get_contents(config_path('googlecloud.json'));
            $storage = new StorageClients([
                'keyFile' => json_decode($googleConfigFile, true)
            ]);
            $storageBucketName = config('googlecloud.storage_bucket');
            $bucket = $storage->bucket($storageBucketName);
            $fileSource = fopen($file, 'r');
            $newFolderName = $var_name . '_' . date("Y-m-d") . '_' . date("H:i:s");
            $googleCloudStoragePath = $path . $fileName;
            /* Upload a file to the bucket.
            Using Predefined ACLs to manage object permissions, you may
            upload a file and give read access to anyone with the URL.*/
            $bucket->upload($fileSource, [
                'predefinedAcl' => 'publicRead',
                'name' => $googleCloudStoragePath
            ]);

            $fileUploaded = $googleCloudStoragePath;
        } else {
            if ($set == 'update') {
                $fileUploaded = isset($data) ? @$data->$var_name : null;
            } else {
                $fileUploaded = null;
            }
        }

        return $fileUploaded;
    }
}
