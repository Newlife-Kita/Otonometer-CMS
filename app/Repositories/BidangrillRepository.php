<?php

namespace App\Repositories;

use App\Models\Bidangrill;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class BidangrillRepository
 * @package App\Repositories
 * @version October 18, 2023, 9:37 am UTC
 *
 * @method Bidangrill findWithoutFail($id, $columns = ['*'])
 * @method Bidangrill find($id, $columns = ['*'])
 * @method Bidangrill first($columns = ['*'])
*/
class BidangrillRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_bidang',
        'id_wilayah',
        'nilai',
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
        return Bidangrill::class;
    }
}
