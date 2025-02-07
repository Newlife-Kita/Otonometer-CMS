<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Paket",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="nama_paket",
 *          description="nama_paket",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="biaya",
 *          description="biaya",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="periode",
 *          description="periode",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="periode_label",
 *          description="periode_label",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="string"
 *      )
 * )
 */
class Paket extends Model
{
    use SoftDeletes;
    use HasTranslations;

    public $table = 'master_paket';

    public $translatable = ['nama_paket'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'nama_paket',
        'icon',
        'biaya',
        'periode',
        'periode_label',
        'total_download',
        'total_save',
        'total_collection',
        'total_save_per_collection',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nama_paket' => 'string',
        'periode' => 'integer',
        'periode_label' => 'string',
        'status' => 'string'
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function memberPakets()
    {
        return $this->hasMany(\App\Models\MemberPaket::class);
    }
}
