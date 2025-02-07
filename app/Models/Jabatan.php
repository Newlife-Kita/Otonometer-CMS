<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Jabatan",
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
 *          property="nama",
 *          description="nama",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="tipe",
 *          description="tipe",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="urutan",
 *          description="urutan",
 *          type="integer",
 *          format="int32"
 *      )
 * )
 */
class Jabatan extends Model
{
    use SoftDeletes;
    use HasTranslations;

    public $table = 'master_jabatan';
    public $translatable = ['nama'];
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_increament',
        'kode',
        'nama',
        'tipe',
        'tipe_wilayah',
        'urutan'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_increament' => 'integer',
        'kode' => 'string',
        'nama' => 'string',
        'tipe' => 'string',
        'urutan' => 'integer'
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function sukuDinasPejabats()
    {
        return $this->hasMany(\App\Models\SukuDinasPejabat::class);
    }
}
