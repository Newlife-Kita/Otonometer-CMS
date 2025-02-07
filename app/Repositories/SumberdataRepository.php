<?php

namespace App\Repositories;

use App\Models\Sumberdata;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class SumberdataRepository
 * @package App\Repositories
 * @version November 24, 2023, 6:11 am UTC
 *
 * @method Sumberdata findWithoutFail($id, $columns = ['*'])
 * @method Sumberdata find($id, $columns = ['*'])
 * @method Sumberdata first($columns = ['*'])
*/
class SumberdataRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'description'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Sumberdata::class;
    }
    

    public function getArrayData()
    {

        $collection = Sumberdata::select('id', 'description')->get();

        $array = [];

        foreach ($collection as $item) {
            $array[$item['id']] = $item['description'];
        }

        return $array;
    }
}
