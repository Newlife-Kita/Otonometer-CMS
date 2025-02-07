<?php

namespace App\Repositories;

use App\Models\Memberaktifitas;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class MemberaktifitasRepository
 * @package App\Repositories
 * @version October 25, 2023, 8:40 am UTC
 *
 * @method Memberaktifitas findWithoutFail($id, $columns = ['*'])
 * @method Memberaktifitas find($id, $columns = ['*'])
 * @method Memberaktifitas first($columns = ['*'])
*/
class MemberaktifitasRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_member',
        'id_kategori',
        'id_wilayah',
        'tahun'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Memberaktifitas::class;
    }
}
