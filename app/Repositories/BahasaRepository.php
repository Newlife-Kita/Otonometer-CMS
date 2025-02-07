<?php

namespace App\Repositories;

use App\Models\Bahasa;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class BahasaRepository
 * @package App\Repositories
 * @version October 25, 2023, 8:31 am UTC
 *
 * @method Bahasa findWithoutFail($id, $columns = ['*'])
 * @method Bahasa find($id, $columns = ['*'])
 * @method Bahasa first($columns = ['*'])
*/
class BahasaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'code',
        'label',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Bahasa::class;
    }
}
