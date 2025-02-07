<?php

namespace App\Repositories;

use App\Models\Ekonomi;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class EkonomiRepository
 * @package App\Repositories
 * @version October 19, 2023, 6:44 am UTC
 *
 * @method Ekonomi findWithoutFail($id, $columns = ['*'])
 * @method Ekonomi find($id, $columns = ['*'])
 * @method Ekonomi first($columns = ['*'])
 */
class EkonomiRepository extends BaseRepository
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
        return Ekonomi::class;
    }


    public function getArrayPDRB()
    {
        $collection = Ekonomi::select('kode', 'nama')->get();

        $array = [];

        foreach ($collection as $item) {
            $array[$item['kode']] = $item['nama'];
        }

        return $array;
    }
}
