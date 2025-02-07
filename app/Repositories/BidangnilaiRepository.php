<?php

namespace App\Repositories;

use App\Models\Bidangnilai;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class BidangnilaiRepository
 * @package App\Repositories
 * @version November 7, 2023, 11:34 am UTC
 *
 * @method Bidangnilai findWithoutFail($id, $columns = ['*'])
 * @method Bidangnilai find($id, $columns = ['*'])
 * @method Bidangnilai first($columns = ['*'])
*/
class BidangnilaiRepository extends BaseRepository
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
        return Bidangnilai::class;
    }
}
