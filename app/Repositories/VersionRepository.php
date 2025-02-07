<?php

namespace App\Repositories;

use App\Models\Version;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class VersionRepository
 * @package App\Repositories
 * @version February 28, 2024, 3:14 pm WIB
 *
 * @method Version findWithoutFail($id, $columns = ['*'])
 * @method Version find($id, $columns = ['*'])
 * @method Version first($columns = ['*'])
*/
class VersionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Version::class;
    }
}
