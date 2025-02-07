<?php

namespace App\Import;

use App\Models\Jabatan;
use App\Models\DprdTemp;
use App\Models\Wilayah;
use App\Models\Partai;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\AfterImport;



class PimpinanDprdImport implements ToCollection, WithHeadingRow
{
    private $wilayah;
    private $jabatan;

    public function setWilayah(Wilayah $wilayah)
    {
        $this->wilayah = $wilayah;
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            if ((@$row['awal_masa_jabatan_ketua'] && @$row['akhir_masa_jabatan_ketua']) || (@$row['awal_masa_jabatan_wakil'] && @$row['akhir_masa_jabatan_wakil'])) {


                if (@$row["nama_ketua_dprd"]) {

                    $jabatan = Jabatan::where('nama', 'LIKE', "%Ketua DPRD%")->where('tipe_wilayah', $this->wilayah->tipe)->first()?->id;
                    $partai = Partai::where('nama', 'LIKE', "%{$row['partai_ketua_dprd']}")->first()?->id;
                    for ($year = $row['awal_masa_jabatan_ketua']; $year < $row['akhir_masa_jabatan_ketua']; $year++) {
                        DprdTemp::updateOrCreate([
                            'tahun' => $year,
                            'id_wilayah' => $this->wilayah->id,
                            'nama_lengkap' => $row["nama_ketua_dprd"],
                            'id_jabatan' => $jabatan,
                            'id_partai' => $partai,
                            'tahun_lantik' => $row['awal_masa_jabatan_ketua'],
                            'tahun_akhir' => $row['akhir_masa_jabatan_ketua']
                        ]);
                    }
                }

                if (@$row["nama_wakil_ketua_dprd"]) {
                    $jabatan = Jabatan::where('nama', 'LIKE', "%Wakil Ketua DPRD%")->where('tipe_wilayah', $this->wilayah->tipe)->first()?->id;
                    $partai = Partai::where('nama', 'LIKE', "%{$row['partai_wakil_ketua_dprd']}")->first()?->id;
                    for ($year = $row['awal_masa_jabatan_wakil']; $year < $row['akhir_masa_jabatan_wakil']; $year++) {
                        DprdTemp::updateOrCreate([
                            'tahun' => $year,
                            'id_wilayah' => $this->wilayah->id,
                            'nama_lengkap' => $row["nama_wakil_ketua_dprd"],
                            'id_jabatan' => $jabatan,
                            'id_partai' => $partai,
                            'tahun_lantik' => $row['awal_masa_jabatan_wakil'],
                            'tahun_akhir' => $row['akhir_masa_jabatan_wakil']
                        ]);
                    }
                }
            }
        }
    }
}
