<?php

namespace App\Repositories;

use App\Models\HomepagePict;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class HomepagePictRepository
 * @package App\Repositories
 * @version October 25, 2024, 9:55 am WIB
 *
 * @method HomepagePict findWithoutFail($id, $columns = ['*'])
 * @method HomepagePict find($id, $columns = ['*'])
 * @method HomepagePict first($columns = ['*'])
*/
class HomepagePictRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'link_light_mode',
        'link_dark_mode'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return HomepagePict::class;
    }
}
