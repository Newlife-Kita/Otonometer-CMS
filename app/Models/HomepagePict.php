<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="HomepagePict",
 *      required={""},
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="link_light_mode",
 *          description="link_light_mode",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="link_dark_mode",
 *          description="link_dark_mode",
 *          type="string"
 *      )
 * )
 */
class HomepagePict extends Model
{
    use SoftDeletes;

    public $table = 'homepage_picts';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'link_light_mode',
        'link_dark_mode'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'link_light_mode' => 'string',
        'link_dark_mode' => 'string'
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
