<?php

namespace App\Repositories;

use App\Models\Pejabatwilayah;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class PejabatwilayahRepository
 * @package App\Repositories
 * @version October 18, 2023, 9:34 am UTC
 *
 * @method Pejabatwilayah findWithoutFail($id, $columns = ['*'])
 * @method Pejabatwilayah find($id, $columns = ['*'])
 * @method Pejabatwilayah first($columns = ['*'])
*/
class PejabatwilayahRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_wilayah',
        'nama_lengkap',
        'id_jabatan',
        'periode',
        'tahun',
        'created_by',
        'updated_by',
        'history_updated'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Pejabatwilayah::class;
    }
}
