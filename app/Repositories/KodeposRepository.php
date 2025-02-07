<?php

namespace App\Repositories;

use App\Models\Kodepos;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class KodeposRepository
 * @package App\Repositories
 * @version November 21, 2023, 9:41 am UTC
 *
 * @method Kodepos findWithoutFail($id, $columns = ['*'])
 * @method Kodepos find($id, $columns = ['*'])
 * @method Kodepos first($columns = ['*'])
*/
class KodeposRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_wilayah',
        'id_parent',
        'type',
        'kode',
        'nama'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Kodepos::class;
    }
}
