<?php

namespace App\Import;

use App\Models\DprdTemp;
use App\Models\Jabatan;
use App\Models\Komisi;
use App\Models\Partai;
use App\Models\PejabatsudinTemp;
use App\Models\Sudin;
use App\Models\Wilayah;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PejabatsudinImport implements ToCollection, WithHeadingRow
{
    private $wilayah;
    private $sudin;


    public function setWilayah(Wilayah $wilayah)
    {
        $this->wilayah = $wilayah;
    }

    public function setSudin(Sudin $sudin)
    {
        $this->sudin = $sudin;
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            if ($row['nama_lengkap'] && $row['tahun_lantik'] && $row['tahun_akhir']) {

                $jabatan = $row['jabatan'] ? $row['jabatan'] : 'null';

                $idJabatan = Jabatan::where('nama', 'LIKE', "%$jabatan%")->where('tipe_wilayah', $this->wilayah->tipe)->where('tipe', 'dinas')->first()?->id;
                for ($year = $row['tahun_lantik']; $year < $row['tahun_akhir']; $year++) {
                    PejabatsudinTemp::updateOrCreate([
                        'tahun' => $year,
                        'tahun_lantik' => $row['tahun_lantik'],
                        'tahun_akhir' => $row['tahun_akhir'],
                        'id_suku_dinas' => $this->sudin->id,
                        'nama_lengkap' => $row['nama_lengkap'],
                    ], [
                        'id_jabatan' => $idJabatan,
                        'nip' => $row['nip'],
                        'contact' => $row['kontak'],
                        'email' => $row['email']
                    ]);
                }
            }
        }
    }
}
