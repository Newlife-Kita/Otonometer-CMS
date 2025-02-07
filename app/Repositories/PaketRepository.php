<?php

namespace App\Repositories;

use App\Models\Paket;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class PaketRepository
 * @package App\Repositories
 * @version October 25, 2023, 8:37 am UTC
 *
 * @method Paket findWithoutFail($id, $columns = ['*'])
 * @method Paket find($id, $columns = ['*'])
 * @method Paket first($columns = ['*'])
*/
class PaketRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nama_paket',
        'biaya',
        'periode',
        'periode_label',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Paket::class;
    }
}
