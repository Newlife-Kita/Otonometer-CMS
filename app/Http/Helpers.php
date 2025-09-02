<?php

use App\User;
// use Aws\S3\S3Client;
use App\Models\Bidang;
use App\Models\Wilayah;
use Illuminate\Support\Carbon;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Auth;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

// if (!function_exists('getS3Client')) {
//     function getS3Client()
//     {
//         return new S3Client([
//             'region' => config('filesystems.disks.s3.region'),
//             'version' => 'latest',
//             'credentials' => [
//                 'key' => config('filesystems.disks.s3.key'),
//                 'secret' => config('filesystems.disks.s3.secret'),
//             ],
//         ]);
//     }
// }


if (!function_exists('saveImage')) {
    function saveImage($image, $storage, $isUpdate = false, $model = "")
    {
        $dir = Storage::directories();

        if (!in_array('public/' . $storage . '/', $dir)) {
            Storage::makeDirectory('public/' . $storage . '/');
        }

        if (!empty($image)) {
            $fileImg = uniqid() . '.' . $image->getClientOriginalExtension();
            if ($isUpdate) {
                @Storage::delete('public/' . $storage . '/' . $model);
            }
            Storage::put('public/' . $storage . '/' . $fileImg, File::get($image));
            $image = $fileImg;
        } else {
            if ($isUpdate) {
                $image = $model;
            } else {
                $image = NULL;
            }
        }
        return $image;
    }
}

if (!function_exists('saveImageOriginalName')) {
    function saveImageOriginalName($image, $storage, $isUpdate = false, $current_image = "")
    {
        $dir = Storage::directories();

        if (!in_array('public/' . $storage . '/', $dir)) {
            Storage::makeDirectory('public/' . $storage . '/');
        }

        if (!empty($image)) {
            $fileImg = getNameFile($image->getClientOriginalName());
            if ($isUpdate) {
                @Storage::delete('public/' . $storage . '/' . $current_image);
            }
            Storage::put('public/' . $storage . '/' . $fileImg, File::get($image));
            $image = $fileImg;
        } else {
            if ($isUpdate) {
                $image = $current_image;
            } else {
                $image = NULL;
            }
        }
        return $image;
    }
}

if (!function_exists('getNameFile')) {
    function getNameFile($slug, $val = 0)
    {

        if ($val > 0) {
            $data = explode('.', $slug);
            if ($val == 1) {
                $slug = $data[0] . '_' . $val;
            } else {
                $slug = explode("_", $data[0]);
                $length = count($slug);
                $last = $slug[$length - 1];
                $slug[$length - 1] = $last + 1;
                $slug = implode('_', $slug);
            }
            $slug = $slug . '.' . $data[1];
        }

        if (@Storage::exists($slug)) {
            $result = getNameFile($slug, $val + 1);
            return $result;
        }

        return $slug;
    }
}

/**
 * Saving history of user action in json format
 *
 * @param User $user user who does the action
 * @param string [optional] $jsonHistory optional json from already existing model data input whete updating or deleting
 * @param bool [optional] $deleted optional true when deleting
 */

if (!function_exists('savingHistory')) {
    function savingHistory(User $user, ?string $jsonHistory = null, ?bool $deleted = false)
    {
        if (!$jsonHistory) {
            $newData = [[
                'created_by' => $user->id,
                'creator_name' => $user->name,
                'creator_email' => $user->email,
                'time' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s')
            ]];
            return json_encode($newData);
        }

        $oldData = json_decode($jsonHistory, true);
        $oldData[] = [
            ($deleted ? 'deleted_by' : 'updated_by') => $user->id,
            ($deleted ? 'deleter_name' : 'updater_name') => $user->name,
            ($deleted ? 'deleter_email' : 'deleter_email') => $user->email,
            'time' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s')
        ];

        return json_encode($oldData);
    }
}

if (!function_exists('getFirstBidang')) {
    function getFirstBidang($id)
    {
        $bidang = getParentBidang($id);
        if (!empty($bidang)) return end($bidang);
        else return null;
    }
}

if (!function_exists('getParentBidang')) {
    function getParentBidang($id)
    {
        $array = [];
        $bidang = Bidang::find($id);
        if (!empty($bidang)) {
            $array[] = ['id' => $bidang->id, 'name' => $bidang->nama];
            if (!empty(@$bidang->id_parent)) {
                if (!empty(getParentOfParentBidang($bidang->id_parent))) {
                    $array = array_merge($array, getParentOfParentBidang($bidang->id_parent));
                }
            }

            return $array;
        } else {
            return null;
        }
    }
}

if (!function_exists('getParentOfParentBidang')) {
    function getParentOfParentBidang($id)
    {
        $array = [];
        $bidang = Bidang::find($id);
        if (!empty($bidang)) {
            $array[] = ['id' => $bidang->id, 'name' => $bidang->nama];
            if (!empty(@$bidang->id_parent)) {
                if (!empty(getParentOfParentBidang($bidang->id_parent))) {
                    $array = array_merge($array, getParentOfParentBidang($bidang->id_parent));
                }
            }

            return $array;
        } else {
            return null;
        }
    }
}

if (!function_exists("getFileUrl")) {
    function getFileUrl($filePath)
    {
        try {
            if (empty($filePath)) {
                return null;
            }

            return Storage::disk('s3')->temporaryUrl(
                $filePath,
                now()->addMinutes(60)
            );
        } catch (\Exception $e) {
            return null;
        }
    }
}

# gcp
// if (!function_exists("getFileUrl")) {
//     function getFileUrl($filePath)
//     {
//         $googleConfigFile = file_get_contents(config_path('googlecloud.json'));
//         $storage = new StorageClient([
//             'keyFile' => json_decode($googleConfigFile, true)
//         ]);

//         $storageBucketName = config('googlecloud.storage_bucket');
//         $bucket = $storage->bucket($storageBucketName);

//         $object = $bucket->object($filePath);
//         # This URL is valid for 1 hour
//         $url = $object->signedUrl(new \DateTime('next hour'));

//         return $url;
//     }
// }

if (!function_exists('streamFile')) {
    function streamFile($filePath)
    {
        try {
            $stream = Storage::disk('s3')->readStream($filePath);

            if (!$stream) {
                abort(404);
            }

            $mimeType = Storage::disk('s3')->mimeType($filePath);

            return response()->stream(function () use ($stream) {
                fpassthru($stream);
                fclose($stream);
            }, 200, [
                'Content-Type' => $mimeType ?? 'application/octet-stream',
                'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
            ]);
        } catch (\Exception $e) {
            abort(404);
        }
    }
}

// if (!function_exists("streamFile")) {
//     function streamFile($filePath)
//     {
//         $googleConfigFile = file_get_contents(config_path('googlecloud.json'));
//         $storage = new StorageClient([
//             'keyFile' => json_decode($googleConfigFile, true)
//         ]);

//         $storageBucketName = config('googlecloud.storage_bucket');
//         $bucket = $storage->bucket($storageBucketName);

//         $object = $bucket->object($filePath);

//         if (!$object->exists()) {
//             abort(404);
//         }

//         // Stream the file to the user
//         $fileContents = $object->downloadAsStream();
//         $headers = [
//             'Content-Type' => $object->info()['contentType'],
//             'Content-Disposition' => 'inline; filename="' . $object->name() . '"',
//         ];

//         return Response::make(
//             $fileContents->getContents,
//             200,
//             $headers
//         );
//     }
// }

if (!function_exists("check_panel_access")) {
    function check_panel_access()
    {
        if(@Auth::user()->roles[0]->name !== 'admin wilayah'){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
    }
}

if (!function_exists("check_admin_access")) {
    function check_admin_access()
    {
        if(@Auth::user()->roles[0]->name == 'admin wilayah'){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        $wilayah = Wilayah::find(@Auth::user()->id_wilayah);

        if(empty($wilayah)){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
    }
}

if (!function_exists("wilayah_admin_access")) {
    function wilayah_admin_access()
    {
        $wilayah = Wilayah::find(@Auth::user()->id_wilayah);

        if(empty($wilayah)){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return $wilayah;
    }
}
