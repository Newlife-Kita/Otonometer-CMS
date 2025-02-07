<?php

namespace App\Repositories;

use App\Models\Komisi;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class KomisiRepository
 * @package App\Repositories
 * @version October 18, 2023, 9:27 am UTC
 *
 * @method Komisi findWithoutFail($id, $columns = ['*'])
 * @method Komisi find($id, $columns = ['*'])
 * @method Komisi first($columns = ['*'])
*/
class KomisiRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nama'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Komisi::class;
    }

    public function getArrayKomisi(){
        $collection = Komisi::all();

        $array = [];

        foreach($collection as $item){
            $array[$item['id']] = $item['nama'];
        }

        return $array;
    }
}
