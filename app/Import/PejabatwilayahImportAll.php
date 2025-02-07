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



class PejabatwilayahImportAll implements ToCollection, WithHeadingRow
{

    public function collection(Collection $collection)
    {

        foreach ($collection as $row) {
            if ((@$row['awal_masa_jabatan_ketua'] && $row['akhir_masa_jabatan_ketua']) || (@$row['awal_masa_jabatan_wakil'] && @$row['akhir_masa_jabatan_wakil'])) {

                if (@$row["nama_ketua"]) {
                    $namaWilayah = @$row['nama_wilayah'] ?? $row['wilayah'];

                    $wilayah = Wilayah::where('id', @$row['id_wilayah'])->orWhere('kode', @$row['kode_wilayah'])->orWhere('nama', 'LIKE', $namaWilayah)->first();
                    $jabatan = Jabatan::where('nama', 'NOT LIKE', "%Wakil%")->where('tipe_wilayah', $wilayah?->tipe)->where('kode', 'LIKE', '%pemda%')->first()?->id;
                    for ($year = $row['awal_masa_jabatan_ketua']; $year < $row['akhir_masa_jabatan_ketua']; $year++)
                        PejabatwilayahTemp::firstOrCreate([
                            'tahun' => $year,
                            'id_wilayah' => $wilayah->id,
                            'nama_lengkap' => $row["nama_ketua"],
                            'id_jabatan' => $jabatan,
                            'tahun_lantik' => $row['awal_masa_jabatan_ketua'],
                            'tahun_akhir' => $row['akhir_masa_jabatan_ketua']
                        ]);
                }

                if (@$row["nama_wakil_ketua"]) {
                    $namaWilayah = @$row['nama_wilayah'] ?? $row['wilayah'];

                    $wilayah = Wilayah::where('id', @$row['id_wilayah'])->orWhere('kode', @$row['kode_wilayah'])->orWhere('nama', 'LIKE', $namaWilayah)->first();
                    $jabatan = Jabatan::where('nama', 'LIKE', "%Wakil%")->where('tipe_wilayah', $wilayah?->tipe)->where('kode', 'LIKE', '%pemda%')->first()?->id;
                    for ($year = $row['awal_masa_jabatan_wakil']; $year < $row['akhir_masa_jabatan_wakil']; $year++) {
                        PejabatwilayahTemp::firstOrCreate([
                            'tahun' => $year,
                            'id_wilayah' => $wilayah->id,
                            'nama_lengkap' => $row["nama_wakil_ketua"],
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
