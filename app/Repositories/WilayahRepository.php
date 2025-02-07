<?php

namespace App\Repositories;

use App\Models\Wilayah;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class WilayahRepository
 * @package App\Repositories
 * @version October 18, 2023, 7:08 am UTC
 *
 * @method Wilayah findWithoutFail($id, $columns = ['*'])
 * @method Wilayah find($id, $columns = ['*'])
 * @method Wilayah first($columns = ['*'])
 */
class WilayahRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'kode',
        'tipe',
        'nama',
        'alamat_kantor',
        'kodepos',
        'logo',
        'koordinat',
        'id_parent',
        'id_dataran'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Wilayah::class;
    }

    /**
     * Get associcative array of id => nama wilayah
     */
    public function getArrayProvince()
    {
        $collection = Wilayah::select('id', 'nama')->where('tipe', 'propinsi')->orderBy('nama', 'asc')->get();

        $array = [];

        foreach ($collection as $item) {
            $array[$item['id']] = $item['nama'];
        }

        return $array;
    }

    /**
     * Get associcative array of id => nama wilayah
     */
    public function getArrayWilayah()
    {
        $collection = Wilayah::select('id', 'nama')->get();

        $array = [];

        foreach ($collection as $item) {
            $array[$item['id']] = $item['nama'];
        }

        return $array;
    }


    /**
     * Get associcative array of id => nama wilayah
     */
    public function getArrayOptionsWilayah()
    {
        $results = $this->getArrayOptionsHeader();

        $array_result = [];
        if (count($results) > 0) {
            foreach ($results as $res) {
                $array_result[$res['id']] = $res['name'];
            }
        }
        return $array_result;
    }

    private function getArrayOptionsHeader()
    {
        $collection = Wilayah::with('cities')->select('id', 'nama', 'tipe')->whereNull('id_parent')->get();
        $array = [];

        foreach ($collection as $item) {
            $array[] = ['id' => $item['id'], 'name' => $item['nama']];

            if (count($item->cities)) {
                $array = array_merge($array, $this->getArrayOptionsWilayahChild($item->cities));
            }
        }

        return $array;
    }

    private function getArrayOptionsWilayahChild($data)
    {
        $array = [];
        foreach ($data as $dt) {
            $array[] = ['id' => $dt['id'], 'name' => '&nbsp;&nbsp;&nbsp;&nbsp;' . $dt['nama']];
            if (count($dt->cities)) {
                $array = array_merge($array, $this->getArrayOptionsWilayahChild($dt->cities));
            }
        }
        return $array;
    }

    public function getArrayWilayahTableHead()
    {
        $collection = Wilayah::orderByRaw('CAST(kode AS DECIMAL(10,2)) ASC')->get();

        $array = [];


        foreach ($collection as $item) {
            $array[] = ['id' => $item['id'], 'code' => '<b>' . $item['kode'] . '</b>', 'name' => '<b>' . $item['nama'] . '</b>'];
        }

        return $array;
    }

    public function getArrayWilayahTable($id)
    {
        $collection = Wilayah::with('cities')->select('id', 'kode', 'nama', 'tipe')->where('id_parent', $id)->orderBy('nama')->get();
        $array = [];

        foreach ($collection as $item) {
            $array[] = ['id' => $item['id'], 'code' => $item['kode'], 'name' => $item['nama']];

            if (count($item->cities)) {
                $array = array_merge($array, $this->getArrayWilayahTableChild($item->cities));
            }
        }

        return $array;
    }

    public function getArrayWilayahTableProvince($id)
    {
        if (is_numeric($id)) {
            $collection = Wilayah::with('cities')->select('id', 'kode', 'nama', 'tipe')->where('id', $id)->orderBy('nama')->get();
            $array = [];
        } else {
            $collection = $id;
        }

        foreach ($collection as $item) {
            $array[] = ['id' => $item['id'], 'code' => $item['kode'], 'name' => $item['nama']];

            if (count($item->cities)) {
                $array = array_merge($array, $this->getArrayWilayahTableProvince($item->cities));
            }
        }

        return $array;
    }



    private function getArrayWilayahTableChild($data)
    {
        $array = [];
        foreach ($data as $dt) {
            $array[] = ['id' => $dt['id'], 'code' => $dt['kode'], 'name' => $dt['nama']];
            if (count($dt->cities)) {
                $array = array_merge($array, $this->getArrayWilayahTableChild($dt->cities));
            }
        }
        return $array;
    }

    public function getRegionMap(){
        $collection = Wilayah::get();

        $array = [];

        foreach($collection as $item){
            $array[$item->kode] = $item->id;
        }

        return $array;
    }
}
