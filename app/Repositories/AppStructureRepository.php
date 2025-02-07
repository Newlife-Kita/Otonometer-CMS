<?php

namespace App\Repositories;

use App\Models\AppStructure;
use App\Models\AppText;
use Webcore\Generator\Common\BaseRepository;

use function PHPUnit\Framework\isNull;

/**
 * Class AppStructureRepository
 * @package App\Repositories
 * @version May 15, 2024, 11:14 am WIB
 *
 * @method AppStructure findWithoutFail($id, $columns = ['*'])
 * @method AppStructure find($id, $columns = ['*'])
 * @method AppStructure first($columns = ['*'])
 */
class AppStructureRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'type',
        'description',
        'image',
        'parent_node_id',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AppStructure::class;
    }

    public function getToRoot(int $id)
    {
        $toRoot = AppStructure::select('name', 'id', 'parent_node_id', 'type')->where('id', $id)->first();

        if (is_null($toRoot)) {
            return [];
        }

        $toRoot = $this->getParent($toRoot);

        $array = [];
        $this->makeFlat($toRoot, $array);
        return $array;
    }

    private function getParent($model)
    {
        if ($model->parent) {
            $model->parent['child'] = $model->makeHidden(['parent', 'parent_node_id'])->toArray();
            return $this->getParent($model->parent);
        }
        return $model->makeHidden([
            "description",
            "image",
            "parent_node_id",
            "status",
            "created_at",
            "updated_at",
            "deleted_at",
            "property_name",
            "parent"
        ])->toArray();
    }

    private function makeFlat($nestedarray, &$array)
    {

        if (isset($nestedarray['child'])) {
            $child = @$nestedarray['child'];
            unset($nestedarray['child']);
            $array[] = $nestedarray;
            $this->makeFlat($child, $array);
            return;
        }

        $array[] = $nestedarray;
    }

    public function get_first_sektor_parent($id): array
    {
        $collection = AppStructure::find($id);
        if (is_null($collection)) {
            return [];
        }
        $array = [];
        $array[] = ['id' => $collection->id];
        if ($collection->parent_node_id) {
            $array = array_merge($array, $this->get_first_sektor_parent($collection->parent_node_id));
        }
        return $array;
    }

    public function get_sektor_table(int $id = null): array
    {
        $collection = AppStructure::select('id', 'name', 'property_name', 'type', 'parent_node_id')
            ->when(!is_null($id), function ($q) use ($id) {
                return $q->where('parent_node_id', $id);
            })->when(
                is_null($id),
                function ($q) {
                    return $q->whereNull('parent_node_id');
                }
            )->get();

        $array = [];

        foreach ($collection as $item) {
            $cls = [];
            $prefix = '';
            $first_arr = [];
            if (!is_null($id)) {
                $first_arr = $this->get_first_sektor_parent($id);
            }

            foreach ($first_arr as $arr) {
                $cls[] = 'tr-' . $arr['id'];
                $prefix .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            }

            $cls_str = implode(" ", $cls);

            if ($item->type == 'text') {
                $textId = AppText::where('text_id', $item->id)->orderBy('id', 'desc')->first();
            }

            $array[] = [
                'id' => $item->id,
                'name' => $item->name,
                'property_name' => $item->property_name,
                'type' => $item->type,
                'cls_str' => $cls_str,
                'button_prefix' => $prefix,
                'child' => $item->type == 'text' ? false : true,
                'text' => $item->type == 'text' ? $item->id : null
            ];

            if ($item->type != 'text') {
                $array = array_merge($array, $this->get_sektor_table($item->id));
            }
        }

        return $array;
    }
}
