<?php

namespace App\Repositories;

use App\Models\AnggotaDprd;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class AnggotaDprdRepository
 * @package App\Repositories
 * @version October 23, 2023, 11:16 am UTC
 *
 * @method AnggotaDprd findWithoutFail($id, $columns = ['*'])
 * @method AnggotaDprd find($id, $columns = ['*'])
 * @method AnggotaDprd first($columns = ['*'])
*/
class AnggotaDprdRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_wilayah',
        'nama_lengkap',
        'id_komisi',
        'tahun',
        'periode',
        'created_by',
        'updated_by',
        'deleted_by',
        'history_updated',
        'foto'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AnggotaDprd::class;
    }
}
