<?php

namespace App\Import;

use App\Models\Bidang;
use App\Models\Bidangkeuangan;
use App\Models\Wilayah;
use App\Repositories\BidangRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Flash;

class BidangnilaiImport implements ToCollection
{    
    protected $year;
    protected $r = 2; // rows ke bawah di baris berapa
    protected $c = 2; // column ke samping di kolom berapa
    protected $bidangRepo;
    protected $db;
    protected $idsektor;
    protected $label;

    public function setLabelsektor($label)
    {
        return $this->label = $label;
    }

    public function setIdsektor($idsektor)
    {
        return $this->idsektor = $idsektor;
    }

    public function setDatabase($db)
    {
        return $this->db = $db;
    }

    public function setRepo($bidangRepository)
    {
        return $this->bidangRepo = $bidangRepository;
    }

    public function setYear($year): int
    {
        return $this->year = $year;
    }

    public function collection(Collection $rows)
    { 
        $sektor = [];
        $valid = [];
        for($cell = $this->c; $cell < count($rows[0]); $cell++){
            if(!empty($rows[0][$cell])){ $sektor[$cell] = $rows[0][$cell]; }
            // cek apa ini sektor keuangan, kalo tidak masukin ke array
            $bidang = Bidang::where('kode', $rows[0][$cell])->first(); 
            $first_sektor = $this->bidangRepo->get_first_sektor(@$bidang->id);
            if($first_sektor['kode'] != @$this->idsektor) $valid[] = 'false';
            else $valid[] = 'true';
        }
        
        if(in_array('false', $valid)){
            return redirect()->back()->withErrors(['Kode Sektor bukan anakan sektor '. $this->label]);
        }

        // data 
        for($baris = $this->r; $baris < count($rows)-$this->r; $baris++){
            $id_wilayah  = substr($rows[$baris][0],0,1) =='0' ? substr($rows[$baris][0],1,6) : $rows[$baris][0]; //$rows[$baris][0]; 
            $kode_wilayah  = substr($rows[$baris][1],0,1) =='0' ? substr($rows[$baris][1],1,6) : $rows[$baris][1];
            $nama_wilayah  = $rows[$baris][2];  

            if(!empty($id_wilayah)){
                for($cell = $this->c; $cell < count($rows[0]); $cell++){
                    $bidang = Bidang::where('kode', $sektor[$cell])->first(); 
                    $wilayah = Wilayah::where('kode', $id_wilayah)->first();// nanti ganti id kalo udah fixed
                    if(empty($this->db::where(['id_bidang' => @$bidang->id,'id_wilayah' => @$wilayah->id])->first())){
                        $this->db::create([
                            'id_bidang' => @$bidang->id,
                            'id_wilayah' => @$wilayah->id,
                            'kode' => $sektor[$cell],
                            'nilai' => is_numeric($rows[$baris][$cell]) ? $rows[$baris][$cell] : NULL, //floatVal($rows[$baris][$cell]),
                            'created_by' => Auth::user()->id
                        ]);
                    }
                }
            }
        }
    }
}
