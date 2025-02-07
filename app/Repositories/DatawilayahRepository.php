<?php

namespace App\Repositories;

use App\Models\Datawilayah;
use App\User;
use Carbon\Carbon;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class DatawilayahRepository
 * @package App\Repositories
 * @version October 18, 2023, 9:34 am UTC
 *
 * @method Datawilayah findWithoutFail($id, $columns = ['*'])
 * @method Datawilayah find($id, $columns = ['*'])
 * @method Datawilayah first($columns = ['*'])
*/
class DatawilayahRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
     * Configure the Model
     **/
    public function model()
    {
        return Datawilayah::class;
    }

    
}
