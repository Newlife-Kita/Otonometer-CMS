<?php

namespace App\Import;

use App\Models\Bidang;
use App\Models\Bidangrilltemp;
use App\Models\Wilayah;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class RillsImport implements ToCollection
{    
    protected $year;

    public function setYear($year): int
    {
        return $this->year = $year;
    }

    public function collection(Collection $rows)
    {        
        $sektor = [];
        $row_key = 0;
        $tahun = date('Y');
        $kode = '';
        $nama = '';
        $detail = [];
        foreach ($rows as $row) 
        {
            if($row_key == 0){
                foreach($row as $k => $v){
                    if($k > 2){
                        $sektor[$k] = $v;
                    }
                }
            }
            else{             
                foreach($row as $k => $v){
                    if($k == 0){
                        $kode = $v;
                    }
                    elseif($k == 1){
                        $nama = $v;
                    }
                    elseif($k == 2){
                        $tahun = $v;
                    }
                    else{
                        $sektor1 = $sektor[$k];
                        $sektor_split = explode(".", $sektor1);
                        $bidang = Bidang::where('kode', $sektor_split[0])->first();
                        $wilayah = Wilayah::where('kode', $kode)->first();
                        Bidangrilltemp::create([
                            'id_bidang' => @$bidang->id,
                            'kode_bidang' => $sektor_split[0],
                            'nama_bidang' => @$bidang->kode.'.'.@$bidang->nama,
                            'id_wilayah' => @$wilayah->id,
                            'kode_wilayah' => $kode,
                            'nama_wilayah' => $nama,
                            'nilai' => floatVal($v),
                            'tahun' => $this->year, //$tahun,
                            'created_by' => Auth::user()->id
                        ]);
                    }
                }
            }
            $row_key++;
        } 
    }
}
