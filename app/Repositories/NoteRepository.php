<?php

namespace App\Repositories;

use App\Models\Note;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class NoteRepository
 * @package App\Repositories
 * @version November 20, 2023, 7:38 am UTC
 *
 * @method Note findWithoutFail($id, $columns = ['*'])
 * @method Note find($id, $columns = ['*'])
 * @method Note first($columns = ['*'])
 */
class NoteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
        'description',
        'created_by',
        'update_at',
        'updated_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Note::class;
    }

    public function getArrayData()
    {

        $collection = Note::select('id', 'description')->get();

        $array = [];

        foreach ($collection as $item) {
            $array[$item['id']] = $item['description'];
        }

        return $array;
    }
}
