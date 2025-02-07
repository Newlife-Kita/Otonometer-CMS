<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="AnggotaDprd",
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
 *          property="id_komisi",
 *          description="id_komisi",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="tahun",
 *          description="tahun",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="periode",
 *          description="periode",
 *          type="string"
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
 *          property="deleted_by",
 *          description="deleted_by",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="history_updated",
 *          description="history_updated",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="foto",
 *          description="foto",
 *          type="string"
 *      )
 * )
 */
class AnggotaDprd extends Model
{
    use SoftDeletes;

    public $table = 'anggota_dprd';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_wilayah',
        'nama_lengkap',
        'id_komisi',
        'tahun',
        'periode',
        'created_by',
        'updated_by',
        'deleted_by',
        'history_updated',
        'foto'
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
        'id_komisi' => 'integer',
        'id_jabatan' => 'integer',
        'tahun' => 'integer',
        'periode' => 'string',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'history_updated' => 'string',
        'foto' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }


}
