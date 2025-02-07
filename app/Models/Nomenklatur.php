<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Nomenklatur",
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
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="id_parent",
 *          description="id_parent",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="level",
 *          description="level",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="id_increament",
 *          description="id_increament",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="id_satuan",
 *          description="id_satuan",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="multi_select",
 *          description="multi_select",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="string"
 *      )
 * )
 */
class Nomenklatur extends Model
{
    use SoftDeletes;
    // use HasTranslations;

    public $table = 'nomenklatur';
    // public $translatable = ['nama', 'description'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'kode',
        'nama',
        'description',
        'id_bidang',
        'type',
        'id_parent',
        'level',
        'id_increament',
        'id_satuan',
        'multi_select',
        'status',
        'id_notes',
        'id_sumber'
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
        'description' => 'string',
        'id_parent' => 'integer',
        'level' => 'integer',
        'id_increament' => 'string',
        'id_satuan' => 'integer',
        'multi_select' => 'string',
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

    public function childs()
    {
        return $this->hasMany(Nomenklatur::class, 'id_parent', 'id')->orderBy('kode');
    }
}
