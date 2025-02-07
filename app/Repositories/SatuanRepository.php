<?php

namespace App\Repositories;

use App\Models\Satuan;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class SatuanRepository
 * @package App\Repositories
 * @version October 18, 2023, 9:26 am UTC
 *
 * @method Satuan findWithoutFail($id, $columns = ['*'])
 * @method Satuan find($id, $columns = ['*'])
 * @method Satuan first($columns = ['*'])
*/
class SatuanRepository extends BaseRepository
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
        return Satuan::class;
    }
    
    public function getArrayData(){

        $collection = Satuan::select('id', 'nama')->get();

        $array = [];

        foreach($collection as $item){
            $array[$item['id']] = $item['nama'];
        }

        return $array;
    }
}
