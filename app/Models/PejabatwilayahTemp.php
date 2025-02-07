<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Pejabatwilayah",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="id_wilayah",
 *          description="id_wilayah",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="nama_lengkap",
 *          description="nama_lengkap",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="id_jabatan",
 *          description="id_jabatan",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="periode",
 *          description="periode",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="tahun",
 *          description="tahun",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="created_by",
 *          description="created_by",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="updated_by",
 *          description="updated_by",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="history_updated",
 *          description="history_updated",
 *          type="string"
 *      )
 * )
 */
class PejabatwilayahTemp extends Model
{
    use SoftDeletes;

    public $table = 'wilayah_jabatan_temp';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_wilayah',
        'nama_lengkap',
        'id_jabatan',
        'tahun_lantik',
        'tahun_akhir',
        'created_by',
        'updated_by',
        'history_updated',
        'foto', 'tahun'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_wilayah' => 'integer',
        'nama_lengkap' => 'string',
        'id_jabatan' => 'integer',
        'tahun_lantik' => 'integer',
        'tahun_akhir' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'history_updated' => 'string',
        'foto' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function jabatan()
    {
        return $this->hasOne(Jabatan::class, 'id', 'id_jabatan');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah', 'id');
    }
}
