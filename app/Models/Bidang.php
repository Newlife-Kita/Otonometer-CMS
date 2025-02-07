<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Bidang",
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
 *          property="id_satuan",
 *          description="id_satuan",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="id_parent",
 *          description="id_parent",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="string"
 *      )
 * )
 */
class Bidang extends Model
{
    use SoftDeletes;
    use HasTranslations;

    public $table = 'master_bidang';
    public $translatable = ['nama', 'description'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'kode',
        'nama',
        'description',
        'id_parent',
        'level',
        'id_increament',
        'id_satuan',
        'multi_select',
        'tahun_nomenklatur',
        'flagging',
        'contain_data',
        'status',
        'id_notes',
        'id_sumber',
        'contain_alert',
        'content_alert',
        'flaging_statistik',
        'summable',
        'root_selection'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'kode' => 'string',
        'nama' => 'string',
        'multi_select' => 'string',
        'id_parent' => 'integer',
        'status' => 'string'
    ];

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public function childs()
    {
        return $this->hasMany(Bidang::class, 'id_parent', 'id')->orderBy('kode');
    }

    public function parent()
    {
        return $this->hasOne(Bidang::class, 'id', 'id_parent');
    }
}
