<?php

namespace App\Repositories;

use App\Models\Bidangperkapita;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class BidangperkapitaRepository
 * @package App\Repositories
 * @version October 18, 2023, 9:37 am UTC
 *
 * @method Bidangperkapita findWithoutFail($id, $columns = ['*'])
 * @method Bidangperkapita find($id, $columns = ['*'])
 * @method Bidangperkapita first($columns = ['*'])
*/
class BidangperkapitaRepository extends BaseRepository
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
        return Bidangperkapita::class;
    }
}
