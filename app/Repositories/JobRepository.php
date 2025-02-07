<?php

namespace App\Repositories;

use App\Models\Job;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class JobRepository
 * @package App\Repositories
 * @version November 6, 2023, 7:50 am UTC
 *
 * @method Job findWithoutFail($id, $columns = ['*'])
 * @method Job find($id, $columns = ['*'])
 * @method Job first($columns = ['*'])
*/
class JobRepository extends BaseRepository
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
        return Job::class;
    }
}
