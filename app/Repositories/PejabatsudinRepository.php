<?php

namespace App\Repositories;

use App\Models\Pejabatsudin;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class PejabatsudinRepository
 * @package App\Repositories
 * @version October 18, 2023, 9:32 am UTC
 *
 * @method Pejabatsudin findWithoutFail($id, $columns = ['*'])
 * @method Pejabatsudin find($id, $columns = ['*'])
 * @method Pejabatsudin first($columns = ['*'])
 */
class PejabatsudinRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_suku_dinas',
        'nama_lengkap',
        'id_jabatan',
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
        return Pejabatsudin::class;
    }
}
