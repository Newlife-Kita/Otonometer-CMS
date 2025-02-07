<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Pejabatsudin",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="id_suku_dinas",
 *          description="id_suku_dinas",
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
 *          property="foto",
 *          description="foto",
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
 *          property="history_updated",
 *          description="history_updated",
 *          type="string"
 *      )
 * )
 */
class Pejabatsudin extends Model
{
    use SoftDeletes;

    public $table = 'suku_dinas_pejabat';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_suku_dinas',
        'nama_lengkap',
        'id_jabatan',
        'tahun_lantik',
        'tahun_akhir',
        'foto',
        'nip',
        'contact',
        'email',
        'tahun',
        'created_by',
        'updated_by',
        'history_updated'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_suku_dinas' => 'integer',
        'nama_lengkap' => 'string',
        'id_jabatan' => 'integer',
        'tahun_lantik' => 'integer',
        'tahun_akhir' => 'integer',
        'foto' => 'string',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'history_updated' => 'string'
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
    public function masterJabatan()
    {
        return $this->hasOne(Jabatan::class,'id','id_jabatan');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function sukuDinas()
    {
        return $this->hasOne(Sudin::class,'id','id_suku_dinas');
    }
}
