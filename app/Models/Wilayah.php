<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Wilayah",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="kode",
 *          description="kode",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="tipe",
 *          description="tipe",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="nama",
 *          description="nama",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="alamat_kantor",
 *          description="alamat_kantor",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="kodepos",
 *          description="kodepos",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="logo",
 *          description="logo",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="koordinat",
 *          description="koordinat",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="id_parent",
 *          description="id_parent",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="id_dataran",
 *          description="id_dataran",
 *          type="integer",
 *          format="int32"
 *      )
 * )
 */

// Ganti Master Wilayah ke Wilayah

class Wilayah extends Model
{
    use SoftDeletes;

    use HasTranslations;

    public $table = 'master_wilayah';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $translatable = [
        'nama'
    ];


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_increament',
        'kode',
        'tipe',
        'nama',
        'alamat_kantor_pemerintahan',
        'alamat_kantor_dprd',
        'kodepos',
        'logo',
        'longitude',
        'latitude',
        'id_parent',
        'id_ekonomi',
        'id_dataran',
        'has_data',
        'tahun_pendirian',
        'tahun_pembubaran',
        'peta_dark_mode',
        'peta_light_mode',
        'has_data'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'kode' => 'string',
        'tipe' => 'string',
        'nama' => 'string',
        'alamat_kantor_pemerintahan' => 'string',
        'alamat_kantor_dprd' => 'string',
        'kodepos' => 'string',
        'logo' => 'string',
        'longitude' => 'string',
        'latitude' => 'string',
        'id_parent' => 'integer',
        'id_dataran' => 'integer',
        'tahun_pendirian' => 'string',
        'tahun_pembubaran' => 'string'
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
    public function masterDataran()
    {
        return $this->hasOne(Dataran::class, 'id', 'id_dataran');
    }

    public function cities()
    {
        return $this->hasMany(Wilayah::class, 'id_parent', 'id')->orderBy('nama');
    }

    public function province()
    {
        return $this->belongsTo(Wilayah::class, 'id_parent', 'id')->orderBy('nama');
    }
}
