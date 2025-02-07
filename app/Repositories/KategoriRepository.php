<?php

namespace App\Repositories;

use App\Models\Kategori;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class KategoriRepository
 * @package App\Repositories
 * @version October 25, 2023, 8:34 am UTC
 *
 * @method Kategori findWithoutFail($id, $columns = ['*'])
 * @method Kategori find($id, $columns = ['*'])
 * @method Kategori first($columns = ['*'])
*/
class KategoriRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'kode',
        'nama',
        'icon'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Kategori::class;
    }
}
