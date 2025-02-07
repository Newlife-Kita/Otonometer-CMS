<?php

namespace App\Repositories;

use App\Models\Memberpaket;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class MemberpaketRepository
 * @package App\Repositories
 * @version October 25, 2023, 8:38 am UTC
 *
 * @method Memberpaket findWithoutFail($id, $columns = ['*'])
 * @method Memberpaket find($id, $columns = ['*'])
 * @method Memberpaket first($columns = ['*'])
*/
class MemberpaketRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_paket',
        'id_member',
        'tanggal_mulai',
        'tanggal_akhir'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Memberpaket::class;
    }
}
