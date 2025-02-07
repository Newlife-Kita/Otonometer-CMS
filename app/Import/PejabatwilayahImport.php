<?php

namespace App\Import;

use App\Models\Jabatan;
use App\Models\PejabatwilayahTemp;
use App\Models\Wilayah;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\AfterImport;



class PejabatwilayahImport implements ToCollection, WithHeadingRow
{
    private $wilayah;
    private $jabatan;

    public function setWilayah(Wilayah $wilayah)
    {
        $this->wilayah = $wilayah;
        switch ($wilayah->tipe) {
            case "kabupaten":
                $this->jabatan = 'bupati';
                break;
            case "propinsi":
                $this->jabatan = 'gubernur';
                break;
            default:
                $this->jabatan = 'walikota';
                break;
        }
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            if (($row['awal_masa_jabatan_ketua'] && $row['akhir_masa_jabatan_ketua']) || (@$row['awal_masa_jabatan_wakil'] && @$row['akhir_masa_jabatan_wakil'])) {

                if ($row["nama_{$this->jabatan}"]) {
                    $jabatan = Jabatan::where('nama', 'LIKE', "%" . ucfirst($this->jabatan) . "%")->where('tipe_wilayah', $this->wilayah->tipe)->first()?->id;
                    for ($year = $row['awal_masa_jabatan_ketua']; $year < $row['akhir_masa_jabatan_ketua']; $year++)
                        PejabatwilayahTemp::updateOrCreate([
                            'tahun' => $year,
                            'id_wilayah' => $this->wilayah->id,
                            'nama_lengkap' => $row["nama_{$this->jabatan}"],
                            'id_jabatan' => $jabatan,
                            'tahun_lantik' => $row['awal_masa_jabatan_ketua'],
                            'tahun_akhir' => $row['akhir_masa_jabatan_ketua']
                        ]);
                }

                if (@$row["nama_wakil_{$this->jabatan}"]) {
                    $jabatan = Jabatan::where('nama', 'LIKE', "%Wakil " . ucfirst($this->jabatan) . "%")->where('tipe_wilayah', $this->wilayah->tipe)->first()?->id;
                    for ($year = $row['awal_masa_jabatan_wakil']; $year < $row['akhir_masa_jabatan_wakil']; $year++) {
                        PejabatwilayahTemp::updateOrCreate([
                            'tahun' => $year,
                            'id_wilayah' => $this->wilayah->id,
                            'nama_lengkap' => $row["nama_wakil_{$this->jabatan}"],
                            'id_jabatan' => $jabatan,
                            'tahun_lantik' => $row['awal_masa_jabatan_wakil'],
                            'tahun_akhir' => $row['akhir_masa_jabatan_wakil']
                        ]);
                    }
                }
            }
        }
    }
}
