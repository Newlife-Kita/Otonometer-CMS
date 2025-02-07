<?php

namespace App\Import;

use App\Models\DprdTemp;
use App\Models\Jabatan;
use App\Models\Komisi;
use App\Models\Partai;
use App\Models\Wilayah;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DprdImport implements ToCollection, WithHeadingRow
{
    private $wilayah;

    public function setWilayah(Wilayah $wilayah)
    {
        $this->wilayah = $wilayah;
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            if ($row['nama_lengkap'] && $row['tahun_lantik'] && $row['tahun_akhir']) {
                $jabatan = $row['jabatan'] ? $row['jabatan'] : 'null';
                $komisi = $row['komisi'] ? $row['komisi'] : 'null';
                $partai = $row['partai'] ? $row['partai'] : 'null';
                $jabatan = Jabatan::where('nama', 'LIKE', "%$jabatan%")->where('tipe_wilayah', $this->wilayah->tipe)->where('tipe', 'dprd')->first()?->id;
                $komisi = Komisi::where('nama', 'LIKE', "%$komisi%")->first()?->id;
                $partai = Partai::where('nama', 'LIKE', "%$partai")->first()?->id;
                $tahunLantik = $row['tahun_lantik'];
                $tahunAkhir = $row['tahun_akhir'];
                for ($year = $row['tahun_lantik']; $year < $row['tahun_akhir']; $year++) {
                    DprdTemp::updateOrCreate([
                        'nama_lengkap' => $row['nama_lengkap'],
                        'id_wilayah' => $this->wilayah->id,
                        'id_jabatan' => $jabatan,
                        'id_komisi' => $komisi,
                        'id_partai' => $partai,
                        'tahun_lantik' => $tahunLantik,
                        'tahun_akhir' => $tahunAkhir,
                        'tahun' => $year
                    ]);
                }
            }
        }
    }
}
