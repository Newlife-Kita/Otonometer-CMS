<?php

namespace App\Repositories;

use App\Models\Refrence;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class RefrenceRepository
 * @package App\Repositories
 * @version November 7, 2023, 7:24 am UTC
 *
 * @method Refrence findWithoutFail($id, $columns = ['*'])
 * @method Refrence find($id, $columns = ['*'])
 * @method Refrence first($columns = ['*'])
*/
class RefrenceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Refrence::class;
    }
}
