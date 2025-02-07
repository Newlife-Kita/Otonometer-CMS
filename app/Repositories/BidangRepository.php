<?php

namespace App\Repositories;

use App\Models\Bidang;
use Exception;
use Illuminate\Support\Facades\DB;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class BidangRepository
 * @package App\Repositories
 * @version October 18, 2023, 9:29 am UTC
 *
 * @method Bidang findWithoutFail($id, $columns = ['*'])
 * @method Bidang find($id, $columns = ['*'])
 * @method Bidang first($columns = ['*'])
 */
class BidangRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'kode',
        'nama',
        'id_parent',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Bidang::class;
    }

    public function get_bidang_options($id, $prefix = '')
    {
        $collection = $this->get_nomenclature_child($id);

        $array = [];
        $prefix = $prefix . ' &nbsp;&nbsp;&nbsp;&nbsp;';
        foreach ($collection as $item) {
            $pref = '';
            for ($a = 0; $a < intval($item['level']); $a++) {
                $pref .= $prefix;
            }
            $array[$item['id']] = $item['kode'] . $pref . $item['nama'];
        }
        return $array;
    }

    public function get_nomenclature()
    {
        $collection = Bidang::with('childs')->whereNull('id_parent')->where('status', 'tampil')->orderBy('kode', 'asc')->get();
        $array = [];

        foreach ($collection as $item) {
            $array[] = [
                'id' => $item->id,
                'kode' => $item->kode,
                'nama' => $item->nama,
                'description' => $item->description,
                'level' => $item->level,
                'id_parent' => $item->id_parent,
                'id_satuan' => $item->id_satuan,
                'multi_select' => $item->multi_select
            ];

            if (count($item->childs)) {
                $array = array_merge($array, $this->get_nomenclature_child($item['id']));
            }
        }

        return $array;
    }

    function get_nomenclature_child($id)
    {
        $collection = Bidang::with('childs')->where('id_parent', $id)->orderBy('kode', 'asc')->get();
        $array = [];

        foreach ($collection as $item) {
            $array[] = [
                'id' => $item->id,
                'kode' => $item->kode,
                'nama' => $item->nama,
                'description' => $item->description,
                'level' => $item->level,
                'id_parent' => $item->id_parent,
                'id_satuan' => $item->id_satuan,
                'multi_select' => $item->multi_select
            ];

            if (count($item->childs)) {
                $array = array_merge($array, $this->get_nomenclature_child($item['id']));
            }
        }

        return $array;
    }

    public function getNomenclature()
    {
        $collection = Bidang::select('id', 'nama')->whereNull('id_parent')->get();

        $array = [];

        foreach ($collection as $item) {
            $array[$item['id']] = $item['nama'];
        }

        return $array;
    }

    public function getArrayParentSektor()
    {
        $collection = Bidang::select('id', 'nama')->whereNull('id_parent')->get();

        $array = [];

        foreach ($collection as $item) {
            $array[$item['id']] = $item['nama'];
        }

        return $array;
    }

    public function getArraySektor()
    {
        $collection = Bidang::select('id', 'nama')->get();

        $array = [];

        foreach ($collection as $item) {
            $array[$item['id']] = $item['nama'];
        }

        return $array;
    }

    public function get_array_bidang($parent = null)
    {
        if (intval(@$parent) > 0) $collection = Bidang::with('childs')->where('id_parent', $parent)->where('status', 'tampil')->orderBy('kode', 'asc')->get();
        else $collection = Bidang::with('childs')->whereNull('id_parent')->where('status', 'tampil')->orderBy('kode', 'asc')->get();

        $array = [];
        $result['-1'] = 'Bidang/ Sektor Baru';

        foreach ($collection as $item) {
            $array[] = [
                'id' => $item->id,
                'kode' => $item->kode,
                'nama' => $item->nama,
                'description' => $item->description,
                'level' => $item->level,
                'id_parent' => $item->id_parent,
                'id_satuan' => $item->id_satuan,
                'multi_select' => $item->multi_select
            ];

            if (count($item->childs)) {
                $array = array_merge($array, $this->get_nomenclature_child($item['id']));
            }
        }

        if (count($array) > 0) {
            foreach ($array as $data) {
                $level_str = '';
                for ($str = 2; $str < intval(@$data['level']); $str++) {
                    $level_str .= '&nbsp;&nbsp;&nbsp;&nbsp;';
                }
                $result[$data['id']] = $level_str . $data['kode'] . '.&nbsp;&nbsp;' . $data['nama'];
            }
        }

        return $result;
    }

    public function get_first_sektor($id)
    {
        $collection = Bidang::find(@$id);
        $array = [];
        $array[] = ['id' => @$collection->id, 'kode' => @$collection->kode, 'nama' => @$collection->nama];
        if (@$collection->id_parent) {
            $array = array_merge($array, $this->get_first_sektor_parent($collection->id_parent));
        }
        return end($array);
    }

    public function get_first_sektor_parent($id)
    {
        $collection = Bidang::find($id);
        $array = [];
        $array[] = ['id' => $collection->id, 'kode' => $collection->kode, 'nama' => $collection->nama];
        if ($collection->id_parent) {
            $array = array_merge($array, $this->get_first_sektor_parent($collection->id_parent));
        }
        return $array;
    }

    /**
     * Get associcative array of id => Bidang/ Sektor
     */
    public function getArrayBidangTree($id)
    {
        $collection = Bidang::with('childs')->select('id', 'id_parent', 'nama', 'kode')->where('id_parent', $id)->orderBy('kode', 'asc')->get();
        return $collection;
    }

    public function get_sektor_table($id)
    {
        $collection = Bidang::with('childs')->select('id', 'id_parent', 'nama', 'kode', 'tahun_nomenklatur', 'level', 'summable', 'contain_data', 'contain_alert', 'root_selection')->where('id_parent', $id)->orderBy('id_increament', 'asc')->get();
        $array = [];

        foreach ($collection as $item) {
            $item['tahun_nomenklatur'] = ltrim($item['tahun_nomenklatur'], '[');
            $item['tahun_nomenklatur'] = rtrim($item['tahun_nomenklatur'], ']');
            $prefix = '';
            for ($k = 1; $k < intval($item['level']); $k++) {
                $prefix .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            }

            $first_arr = $this->get_first_sektor_parent($id);

            $cls = [];
            foreach ($first_arr as $arr) {
                $cls[] = 'tr-' . $arr['id'];
            }
            $cls_str = implode(" ", $cls);

            if (count($item->childs)) {
                $array[] = [
                    'id' => $item['id'], 'id_parent' => $item['id_parent'], 'code' => $prefix . $item['kode'], 'name' => $item['nama'], 'level' => $item['level'], 'child' => true,  'cls_str' => $cls_str, 'tahun' => $item['tahun_nomenklatur'], 'tahun_arr' => explode(",", $item['tahun_nomenklatur']),
                    'summable' => $item['summable'], 'root' => $item['root_selection'], 'alert' => $item['contain_alert'], 'data' => $item['contain_data']
                ];

                $array = array_merge($array, $this->get_sektor_table($item['id']));
            } else {
                $array[] = [
                    'id' => $item['id'], 'id_parent' => $item['id_parent'], 'code' => $prefix . $item['kode'], 'name' => $item['nama'], 'level' => $item['level'], 'child' => false,  'cls_str' => $cls_str, 'tahun' => $item['tahun_nomenklatur'], 'tahun_arr' => explode(",", $item['tahun_nomenklatur']),
                    'summable' => $item['summable'], 'root' => $item['root_selection'], 'alert' => $item['contain_alert'], 'data' => $item['contain_data']
                ];
            }
        }

        return $array;
    }

    public function getArrayBidangTable($id)
    {
        $collection = Bidang::with('childs')->select('id', 'id_parent', 'nama', 'kode','level','contain_data')->where('id_parent', $id)->orderBy('kode', 'asc')->get();
        $array = [];

        foreach ($collection as $item) {
            $array[] = ['id' => $item['id'], 'id_parent' => $item['id_parent'], 'code' => $item['kode'], 'name' => $item['nama'], 'level' => $item['level'], 'data' => $item['contain_data']];

            if (count($item->childs)) {
                $array = array_merge($array, $this->getArrayBidangTableChild($item['id']));
            }
        }

        return $array;
    }

    public function getArrayBidangTableChild($id)
    {
        $collection = Bidang::with('childs')->select('id', 'id_parent', 'nama', 'kode','level','contain_data')->where('id_parent', $id)->orderBy('kode', 'asc')->get();
        $array = [];

        foreach ($collection as $item) {
            $array[] = ['id' => $item['id'], 'id_parent' => $item['id_parent'], 'code' => $item['kode'], 'name' => $item['nama'], 'level' => $item['level'], 'data' => $item['contain_data']];

            if (count($item->childs)) {
                $array = array_merge($array, $this->getArrayBidangTableChild($item['id']));
            }
        }

        return $array;
    }

    public function get_sektor_statistik()
    {
        $id = 3;

        $collection = Bidang::select('id', 'id_parent', 'nama', 'kode', 'tahun_nomenklatur', 'level')->where('id_parent', $id)->where('level', 2)->orderBy('id_increament', 'asc')->get();
        $array = [];

        foreach ($collection as $item) {
            $item['tahun_nomenklatur'] = ltrim($item['tahun_nomenklatur'], '[');
            $item['tahun_nomenklatur'] = rtrim($item['tahun_nomenklatur'], ']');
            $prefix = '';
            $first_arr = $this->get_first_sektor_parent($id);
            $cls = [];
            foreach ($first_arr as $arr) {
                $cls[] = 'tr-' . $arr['id'];
            }
            $cls_str = implode(" ", $cls);
            $array[] = ['id' => $item['id'], 'id_parent' => $item['id_parent'], 'code' => $prefix . $item['kode'], 'name' => $item['nama'], 'level' => $item['level'], 'child' => false,  'cls_str' => $cls_str, 'tahun' => $item['tahun_nomenklatur'], 'tahun_arr' => explode(",", $item['tahun_nomenklatur'])];
        }

        return $array;
    }

    public function get_sektorgroup_statistik($id)
    {
        $collection = Bidang::with('childs')->select('id', 'id_parent', 'nama', 'kode', 'tahun_nomenklatur', 'level')->where('id_parent', $id)->orderBy('id_increament', 'asc')->get();
        $array = [];

        foreach ($collection as $item) {
            $item['tahun_nomenklatur'] = ltrim($item['tahun_nomenklatur'], '[');
            $item['tahun_nomenklatur'] = rtrim($item['tahun_nomenklatur'], ']');
            $prefix = '';
            for ($k = 1; $k < intval($item['level']); $k++) {
                $prefix .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            }

            $first_arr = $this->get_first_sektor_parent($id);
            $cls = [];
            foreach ($first_arr as $arr) {
                $cls[] = 'tr-' . $arr['id'];
            }
            $cls_str = implode(" ", $cls);
            if (count($item->childs)) {
                $array[] = ['id' => $item['id'], 'id_parent' => $item['id_parent'], 'code' => $prefix . $item['kode'], 'name' => $item['nama'], 'level' => $item['level'], 'child' => true,  'cls_str' => $cls_str, 'tahun' => $item['tahun_nomenklatur'], 'tahun_arr' => explode(",", $item['tahun_nomenklatur'])];

                $array = array_merge($array, $this->get_sektor_table($item['id']));
            } else {
                $array[] = ['id' => $item['id'], 'id_parent' => $item['id_parent'], 'code' => $prefix . $item['kode'], 'name' => $item['nama'], 'level' => $item['level'], 'child' => false,  'cls_str' => $cls_str, 'tahun' => $item['tahun_nomenklatur'], 'tahun_arr' => explode(",", $item['tahun_nomenklatur'])];
            }
        }

        return $array;
    }

    public function set_root(int $sectorId, string $value)
    {
        DB::beginTransaction();
        try {
            $this->reset_ancestor($sectorId);
            $this->reset_descendant($sectorId);
            Bidang::where('id', $sectorId)->update(["root_selection" => $value]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function reset_ancestor(int $sectorId)
    {
        DB::statement("
        WITH RECURSIVE RecursiveAncestors AS (
            SELECT *
            FROM master_bidang
            WHERE id = ?

            UNION ALL

            SELECT mb.*
            FROM master_bidang mb
            JOIN RecursiveAncestors a ON mb.id = a.id_parent
        )
        UPDATE master_bidang
        SET root_selection = 'n'
        WHERE id IN (
            SELECT id
            FROM RecursiveAncestors
        ) AND id <> ?;
        ", [$sectorId, $sectorId]);
    }

    public function reset_descendant(int $sectorId)
    {
        DB::statement("
        WITH RECURSIVE RecursiveDescedants AS (
            SELECT *
            FROM master_bidang
            WHERE id = ?

            UNION ALL

            SELECT mb.*
            FROM master_bidang mb
            JOIN RecursiveDescedants d ON mb.id_parent = d.id
        )
        UPDATE master_bidang
        SET root_selection = 'n'
        WHERE id IN (
            SELECT id
            FROM RecursiveDescedants
        ) AND id <> ?;
        ", [$sectorId, $sectorId]);
    }

    public function check_tree_status(int $rootId)
    {
        $query = "
        WITH RECURSIVE Descendants AS (
            SELECT id, id_parent
            FROM master_bidang
            WHERE id = ?

            UNION

            SELECT mb.id, mb.id_parent
            FROM master_bidang mb
            JOIN Descendants d ON mb.id_parent = d.id
            ) SELECT p.id as id FROM master_bidang p
            LEFT JOIN master_bidang c ON p.id = c.id_parent
            WHERE c.id IS NULL AND p.id IN (SELECT id FROM Descendants) AND p.deleted_at IS NULL;
        ";

        $result = collect(DB::select($query, [$rootId]))->pluck('id');

        $state = true;

        $nonvalid = [];

        foreach ($result as $item) {
            $cek = $this->check_branch($item);

            if ($cek[0]->value == 0) {
                $state = false;

                $nonvalid = array_merge(collect($this->get_to_root($item))->pluck("value")->toArray(), $nonvalid);
            }
        }


        return [
            "valid" => $state,
            "nonvalid" => array_values(array_unique($nonvalid))
        ];
    }

    private function get_to_root(int $childId)
    {
        $query = "
        WITH RECURSIVE Ancestors AS (
            SELECT id, id_parent, root_selection
            FROM master_bidang
            WHERE id = ?

            UNION
            SELECT mb.id, mb.id_parent, mb.root_selection
            FROM master_bidang mb
            JOIN Ancestors a ON mb.id = a.id_parent
            WHERE mb.id IS NOT NULL
        ) SELECT id as value  FROM Ancestors;
        ";

        $result = DB::select($query, [$childId]);

        return $result;
    }

    public function get_last_selected_parent(int $id)
    {
        $query = "
        WITH RECURSIVE Ancestors AS (
            SELECT id, id_parent, root_selection, level
            FROM master_bidang
            WHERE id = ? AND level >= 2

            UNION

            SELECT mb.id, mb.id_parent, mb.root_selection, mb.level
            FROM master_bidang mb
            JOIN Ancestors a ON mb.id = a.id_parent
            WHERE mb.id IS NOT NULL
            AND mb.level >= 2
        ) SELECT id as value  FROM Ancestors where level  = 2;
        ";

        $result = DB::select($query, [$id])[0]->value;

        return $result;
    }

    private function check_branch(int $childId)
    {
        $query = "
        WITH RECURSIVE Ancestors AS (
            SELECT id, id_parent, root_selection
            FROM master_bidang
            WHERE id = ?

            UNION
            SELECT mb.id, mb.id_parent, mb.root_selection
            FROM master_bidang mb
            JOIN Ancestors a ON mb.id = a.id_parent
            WHERE mb.id IS NOT NULL
        ) SELECT CASE WHEN COUNT(*) = 1 THEN TRUE ELSE FALSE END AS value  FROM Ancestors WHERE root_selection = 'y';
        ";

        $result = DB::select($query, [$childId]);

        return $result;
    }

    public function getChildSectorMap(int $root){
        $query = "
        WITH RECURSIVE Descendants AS (
            SELECT id, id_parent, kode
            FROM master_bidang
            WHERE id = ?

            UNION
            SELECT mb.id, mb.id_parent, mb.kode
            FROM master_bidang mb
            JOIN Descendants a ON mb.id_parent = a.id
        ) SELECT * FROM Descendants ;
        ";

        $result = DB::select($query, [$root]);

        $array = [];

        foreach($result as $item){
            $array[$item->kode] = $item->id;
        }

        return $array;
    }
}
