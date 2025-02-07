<?php

namespace App\Repositories;

use App\Models\Parpol;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class ParpolRepository
 * @package App\Repositories
 * @version October 24, 2023, 9:46 am UTC
 *
 * @method Parpol findWithoutFail($id, $columns = ['*'])
 * @method Parpol find($id, $columns = ['*'])
 * @method Parpol first($columns = ['*'])
*/
class ParpolRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'logo'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Parpol::class;
    }
}
