<?php

namespace App\Repositories;

use App\Models\LogCms;
use App\Models\LogUpload;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class LogUploadRepository
 * @package App\Repositories
 * @version May 3, 2024, 2:51 pm WIB
 *
 * @method LogUpload findWithoutFail($id, $columns = ['*'])
 * @method LogUpload find($id, $columns = ['*'])
 * @method LogUpload first($columns = ['*'])
 */
class LogCmsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'row_uploaded',
        'upload_date',
        'status',
        'upload_type'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return LogCms::class;
    }
}
