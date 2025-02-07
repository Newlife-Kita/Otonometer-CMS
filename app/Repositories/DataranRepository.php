<?php

namespace App\Repositories;

use App\Models\Dataran;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class DataranRepository
 * @package App\Repositories
 * @version October 18, 2023, 6:37 am UTC
 *
 * @method Dataran findWithoutFail($id, $columns = ['*'])
 * @method Dataran find($id, $columns = ['*'])
 * @method Dataran first($columns = ['*'])
*/
class DataranRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'kode',
        'nama',
        'icon',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Dataran::class;
    }

    public function getArrayDataran(){
        $collection = Dataran::select('id', 'nama')->get();

        $array = [];

        foreach($collection as $item){
            $array[$item['id']] = $item['nama'];
        }

        return $array;
    }

    public function getArrayTableDataran(){
        $collection = Dataran::select('id', 'nama')->get();

        $array = [];

        foreach($collection as $item){
            $array[$item['id']] = $item['id'].'.'.$item['nama'];
        }

        return $array;
    }
}
