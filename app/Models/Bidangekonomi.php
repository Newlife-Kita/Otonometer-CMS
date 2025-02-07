<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bidangekonomi extends Model
{
    use SoftDeletes;

    public $table = 'nomenklatur_amount_ekonomi';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_bidang',
        'id_wilayah',
        'kode',
        'nilai',
        'tahun',
        'created_by',
        'upload_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
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
