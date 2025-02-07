<?php

namespace App\Repositories;

use App\Models\AppText;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class AppTextRepository
 * @package App\Repositories
 * @version May 17, 2024, 1:44 pm WIB
 *
 * @method AppText findWithoutFail($id, $columns = ['*'])
 * @method AppText find($id, $columns = ['*'])
 * @method AppText first($columns = ['*'])
*/
class AppTextRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'text_id',
        'text'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AppText::class;
    }
}
