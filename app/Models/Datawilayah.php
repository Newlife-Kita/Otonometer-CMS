<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Datawilayah",
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
 *          property="tahun",
 *          description="tahun",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="id_sektor",
 *          description="id_sektor",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="nilai_sektor",
 *          description="nilai_sektor",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="ketinggian",
 *          description="ketinggian",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="luas_wilayah",
 *          description="luas_wilayah",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="jumlah_penduduk",
 *          description="jumlah_penduduk",
 *          type="number",
 *          format="float"
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
class Datawilayah extends Model
{
    use SoftDeletes;

    public $table = 'wilayah_info';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_wilayah',
        'tahun',
        'id_sektor',
        'nilai_sektor',
        'ketinggian',
        'luas_wilayah',
        'jumlah_penduduk',
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
        'id_wilayah' => 'integer',
        'tahun' => 'integer',
        'id_sektor' => 'integer',
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
    public function wilayah()
    {
        return $this->hasOne(Wilayah::class, 'id', 'id_wilayah');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function sektor()
    {
        return $this->hasOne(Ekonomi::class, 'id', 'id_sektor');
    }
}
