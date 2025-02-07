<?php

namespace App\Repositories;

use App\Models\Informasi;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class InformasiRepository
 * @package App\Repositories
 * @version November 23, 2023, 4:26 am UTC
 *
 * @method Informasi findWithoutFail($id, $columns = ['*'])
 * @method Informasi find($id, $columns = ['*'])
 * @method Informasi first($columns = ['*'])
*/
class InformasiRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'file',
        'nama_file',
        'status',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Informasi::class;
    }
}
