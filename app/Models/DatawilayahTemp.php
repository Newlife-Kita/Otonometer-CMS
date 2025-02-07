<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DatawilayahTemp extends Model
{
    use SoftDeletes;

    public $table = 'wilayah_info_temp';

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
    ];
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
