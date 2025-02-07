<?php

namespace App\Repositories;

use App\Models\Sudin;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class SudinRepository
 * @package App\Repositories
 * @version October 18, 2023, 9:32 am UTC
 *
 * @method Sudin findWithoutFail($id, $columns = ['*'])
 * @method Sudin find($id, $columns = ['*'])
 * @method Sudin first($columns = ['*'])
*/
class SudinRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_wilayah',
        'nama_sudin',
        'alamat',
        'logo',
        'nama_pic',
        'telp_pic',
        'email_pic',
        'created_by',
        'updated_by',
        'history_updated'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Sudin::class;
    }

    public function getArraySudin(){
        $collection = Sudin::with('wilayah')->get();

        $array = [];

        foreach($collection as $item){
            $wilayahNama = $item->wilayah->nama ?? 'N/A';
            $array["{$item['id']}"] = "{$item->nama_sudin} $wilayahNama $item->id";
        }

        return $array;
    }
}
