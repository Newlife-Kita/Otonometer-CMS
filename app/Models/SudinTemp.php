<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Sudin",
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
 *          property="nama_sudin",
 *          description="nama_sudin",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="alamat",
 *          description="alamat",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="logo",
 *          description="logo",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="nama_pic",
 *          description="nama_pic",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="telp_pic",
 *          description="telp_pic",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email_pic",
 *          description="email_pic",
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
class SudinTemp extends Model
{
    use SoftDeletes;
    use HasTranslations;

    public $table = 'suku_dinas_temp';

    public $translatable = ['nama_sudin'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_wilayah',
        'nama_sudin',
        'alamat',
        'logo',
        'nama_pic',
        'telp_pic',
        'email_pic',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_wilayah' => 'integer',
        'nama_sudin' => 'string',
        'alamat' => 'string',
        'logo' => 'string',
        'nama_pic' => 'string',
        'telp_pic' => 'string',
        'email_pic' => 'string',
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
    public function wilayah()
    {
        return $this->hasOne(Wilayah::class, 'id', 'id_wilayah');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function sukuDinasPejabats()
    {
        return $this->hasMany(Pejabatsudin::class);
    }
}
