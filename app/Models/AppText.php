<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="AppText",
 *      required={""},
 *      @SWG\Property(
 *          property="text",
 *          description="text",
 *          type="string"
 *      )
 * )
 */
class AppText extends Model
{
    use SoftDeletes, HasTranslations;

    public $table = 'app_text';

    public $translatable = ['text'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'text_id',
        'text'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'text' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function textRaw(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                $array = json_decode($attributes['text'], true);
                if (is_null($array)) {
                    $array = Bahasa::get()->pluck('code')->toArray();

                    $array = array_combine($array, array_fill(0, count($array), ""));

                    $array["type"] = "line";

                    return json_encode($array);
                }

                unset($array["type"]);

                $countArray =   array_filter($array, function ($item) {
                    return is_array($item);
                });

                $countText = array_filter($array, function ($item) {
                    return is_string($item);
                });

                if (count($array) == count($countArray)) {
                    $array["type"] = "list";
                } elseif (count($array) == count($countText)) {
                    $array["type"] = "line";
                } else {
                    $array["type"] = "invalid";
                }

                return json_encode($array);
            },
        );
    }

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
