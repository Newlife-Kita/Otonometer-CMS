<?php

namespace App\Import;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Wilayah;
use App\Models\DatawilayahTemp;
use App\Models\Ekonomi;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class DataWilayahImport implements ToCollection, WithHeadingRow
{
    private $tahun;

    public function setYear($tahun)
    {
        $this->tahun = $tahun;
    }

    public function collection(Collection  $collection)
    {
        foreach ($collection as $row) {
            $nama = $row['nama_daerah'];
            $kode = number_format($row['kode'], 2, '.', '');
            $sektor = $row['nama_sektor_unggulan'] ? $row['nama_sektor_unggulan'] : 'null';
            $idWilayah = Wilayah::where(function ($query) use ($nama, $kode) {
                return $query->where('nama', $nama)->orWhere('kode', $kode);
            })->first()?->id;

            if (!$idWilayah) {
                continue;
            }

            DatawilayahTemp::updateOrCreate([
                'id_wilayah' => $idWilayah,
                'tahun' => $this->tahun,
            ], [
                'id_sektor' => Ekonomi::where('nama', 'LIKE', "%$sektor%")->first()?->id ?: Ekonomi::where('kode', 0)->first()?->id,
                'nilai_sektor' => $row['nilai_sektor'],
                'ketinggian' => $row['ketinggian'],
                'luas_wilayah' => $row['luas_wilayah'],
                'jumlah_penduduk' => $row['jumlah_penduduk']
            ]);
        }
    }
}
