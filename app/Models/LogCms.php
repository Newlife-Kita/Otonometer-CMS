<?php

namespace App\Models;

use App\User;
use Eloquent as Model;

/**
 * @SWG\Definition(
 *      definition="LogUpload",
 *      required={""},
 *      @SWG\Property(
 *          property="row_uploaded",
 *          description="row_uploaded",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="upload_type",
 *          description="upload_type",
 *          type="string"
 *      )
 * )
 */
class LogCms extends Model
{

    public $table = 'log_cms';


    public $fillable = [
        'users_id',
        'row_uploaded',
        'row_submitted',
        'upload_date',
        'submit_date',
        'status',
        'upload_type',
        'file_name'
    ];


    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'row_uploaded' => 'integer',
        'status' => 'string',
        'upload_type' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
