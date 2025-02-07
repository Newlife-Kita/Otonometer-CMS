<?php

namespace App\Repositories;

use App\Models\Dprd;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class DprdRepository
 * @package App\Repositories
 * @version October 20, 2023, 7:07 am UTC
 *
 * @method Dprd findWithoutFail($id, $columns = ['*'])
 * @method Dprd find($id, $columns = ['*'])
 * @method Dprd first($columns = ['*'])
*/
class DprdRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_komisi',
        'nama_lengkap',
        'id_jabatan',
        'id_partai',
        'tahun',
        'periode',
        'foto',
        'created_by',
        'updated_by',
        'history_updated'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Dprd::class;
    }
}
