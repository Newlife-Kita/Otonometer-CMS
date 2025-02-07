<?php

namespace App\Repositories;

use App\Models\Nomenklatur;
use App\Models\Nomenklaturtahun;
use Illuminate\Support\Facades\DB;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class NomenklaturtahunRepository
 * @package App\Repositories
 * @version November 17, 2023, 6:21 am UTC
 *
 * @method Nomenklaturtahun findWithoutFail($id, $columns = ['*'])
 * @method Nomenklaturtahun find($id, $columns = ['*'])
 * @method Nomenklaturtahun first($columns = ['*'])
*/
class NomenklaturtahunRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_nomenklatur',
        'tahun',
        'active',
        'default_year'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Nomenklaturtahun::class;
    }

    public function get_tahun_data(){
        $collection = Nomenklaturtahun::selectRaw('distinct(tahun)')->get();
        $array = [];

        // dd(DB::table('nomenklatur_amount_2018')->exists());

        foreach($collection as $item){
            $array[$item['tahun']] = $item['tahun'];
        }

        return $array;
    }

    public function get_nomenklatur_tahun($tahun, $id){
        $nomen = Nomenklaturtahun::with('nomenklatur')->where('tahun', $tahun)->get();
        $result = [];
        foreach($nomen as $nm){
            $result[@$nm->nomenklatur->kode] = $nm->id_nomenklatur; //Nomenklatur::where('id_bidang', @$id)->where('tahun_nomenklatur', @$nm->nomenklatur->kode)->first()->id;
        }
        return $result;
    }
    
    public function merge_bidang($nomenklatur, $id, $repo){
        $first_key = key($nomenklatur); // First element's key
        $first_value = reset($nomenklatur); // First element's value
        $first_sektor = $repo->where('id_bidang', $id)->where('tahun_nomenklatur', $first_key)->first();
        if(empty($first_sektor)){
            return false;
        }

        $bidang = $repo->get_array_nomenclature($first_sektor->id);
        $sektor = [];
        foreach($bidang as $bid){
            $sektor[] = $bid;
            foreach($nomenklatur as $k => $v){
                if($k != $first_key){
                    // cek apa sektor ini ada di nomenklatur
                }
            }
            
        }
        
        // $sektor = [];
        // foreach($data as $k => $v){
        //     $sektor = array_merge($sektor,$this->get_array_bidang($nomenklatur, $v));
        // }

        // $data_bidang = $bidang->getArrayBidangTable($id);
        // $sektor = [];
        // foreach($data_bidang as $bidang){
        //     foreach($data as $k => $v){
        //         $cek = $nomenklatur->where('tahun_nomenklatur', $k)->where('id_bidang', $bidang['id'])->first();
        //         if(!empty($cek)){
        //             $sektor[] = [
        //                 "id" => $bidang["id"],
        //                 "id_nomenklatur" => $cek->id,
        //                 "id_parent" => $bidang["id_parent"],
        //                 "code" => $bidang["code"],
        //                 "name" => $bidang["name"],
        //                 "tahun" => $k,
        //                 "flag" => ''
        //             ];
        //         }
        //         else{

        //         }
        //     }
            
        // }

        return $sektor;
        // dd($nomenklatur, $this->nomenklaturtahunRepository->merge_bidang($nomenklatur, $id, $this->nomenklaturRepository, $this->bidangRepository));
        
    }

    public function get_array_bidang($nomenklatur, $id){
        $array=[];
        $collection = $nomenklatur->with('childs')->where('id_parent', $id)->where('status','tampil')->orderBy('id_increament', 'asc')->get();
        foreach($collection as $item){
            $array[] = [
                'kode' => $item->kode,
                'nama' => $item->nama,	
                'description' => $item->description,
                'tahun' => $item->tahun_nomenklatur,
                'id_parent' => $item->id_parent,
                'id_satuan' => $item->id_satuan,
                'id' => $item->id,
                'id_bidang' => $item->id_bidang,
                'multi_select' => $item->multi_select
            ];

            if(count($item->childs))
            {
                $array = array_merge($array, $this->get_array_bidang($nomenklatur, $item->id));
            }
        }
        
        return $array;
    }

    public function setDefaulYear(int $id): bool{

        Nomenklaturtahun::where('id', '!=', $id)->update(['default_year' => 0]);

        Nomenklaturtahun::where('id', $id)->update(['default_year' => 1]);

        return true;
    }
}
