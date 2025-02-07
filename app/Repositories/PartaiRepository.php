<?php

namespace App\Repositories;

use App\Models\Partai;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class PartaiRepository
 * @package App\Repositories
 * @version October 20, 2023, 6:55 am UTC
 *
 * @method Partai findWithoutFail($id, $columns = ['*'])
 * @method Partai find($id, $columns = ['*'])
 * @method Partai first($columns = ['*'])
*/
class PartaiRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nama',
        'logo'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Partai::class;
    }
    
    public function getArrayPartai(){

        $collection = Partai::select('id', 'nama')->get();

        $array = [];

        foreach($collection as $item){
            $array[$item['id']] = $item['nama'];
        }

        return $array;
    }
}
