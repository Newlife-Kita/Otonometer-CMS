<?php

namespace App\Models;

use App\User;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Bidangrill",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="id_bidang",
 *          description="id_bidang",
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
 *          property="nilai",
 *          description="nilai",
 *          type="number",
 *          format="float"
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
class Bidangrill extends Model
{
    use SoftDeletes;

    public $table = 'bidang_nilai_rill';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_bidang',
        'id_wilayah',
        'nilai',
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
        'id_bidang' => 'integer',
        'id_wilayah' => 'integer',
        'tahun' => 'string',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'history_updated' => 'string'
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

    public function bidang()
    {
        return $this->hasOne(Bidang::class,'id', 'id_bidang');
    }

    public function wilayah()
    {
        return $this->hasOne(Wilayah::class,'id', 'id_wilayah');
    }

    public function admin()
    {
        return $this->hasOne(User::class,'id', 'created_by');
    }
}
