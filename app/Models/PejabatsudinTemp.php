<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PejabatsudinTemp extends Model
{
    use HasFactory;

    use SoftDeletes;

    public $table = 'suku_dinas_pejabat_temp';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_suku_dinas',
        'nama_lengkap',
        'id_jabatan',
        'tahun_lantik',
        'tahun_akhir',
        'foto',
        'nip',
        'contact',
        'email',
        'tahun'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function masterJabatan()
    {
        return $this->hasOne(Jabatan::class, 'id', 'id_jabatan');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function sukuDinas()
    {
        return $this->hasOne(Sudin::class, 'id', 'id_suku_dinas');
    }
}
