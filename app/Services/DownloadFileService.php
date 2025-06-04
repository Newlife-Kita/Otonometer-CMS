<?php
namespace App\Services;

// use Aws\S3\S3Client;

use Aws\Exception\AwsException;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Response;

class DownloadFileService
{
    function downloadFile($filePath)
    {
        try {
            // Baca stream file dari MinIO
            $stream = Storage::disk('minio')->readStream($filePath);

            if (!$stream) {
                abort(404);
            }

            // Deteksi MIME type file (opsional tapi disarankan)
            $mimeType = Storage::disk('minio')->mimeType($filePath) ?? 'application/octet-stream';

            // Response streaming sebagai download
            return response()->stream(function () use ($stream) {
                fpassthru($stream);
                fclose($stream);
            }, 200, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'attachment; filename="' . basename($filePath) . '"',
            ]);
        } catch (\Exception $e) {
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
