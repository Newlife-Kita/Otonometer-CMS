<?php
namespace App\Services;

use Illuminate\Support\Facades\Response;

use Google\Cloud\Storage\StorageClient;

class DownloadFileService
{

    public function downloadFile($filePath)
    {
        $googleConfigFile = file_get_contents(config_path('googlecloud.json'));
        $storage = new StorageClient([
            'keyFile' => json_decode($googleConfigFile, true)
        ]);

        $storageBucketName = config('googlecloud.storage_bucket');
        $bucket = $storage->bucket($storageBucketName);

        $object = $bucket->object($filePath);

        if (!$object->exists()) {
            abort(404);
        }

        // Stream the file to the user
        $fileContents = $object->downloadAsStream();
        $headers = [
            'Content-Type' => $object->info()['contentType'],
            'Content-Disposition' => 'attachment; filename="' . $object->name() . '"',
        ];

        return Response::stream(
            function () use ($fileContents) {
                echo $fileContents->getContents();
            },
            200,
            $headers
        );
    }
}