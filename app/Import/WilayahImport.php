<?php

namespace App\Import;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Wilayah;
use App\Models\Dataran;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class WilayahImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection  $collection)
    {
        foreach ($collection as $row) {
            $id = explode(".", $row["dataran"]);
            $wilayah = Wilayah::where('kode', $row['kode'])->first();

            Wilayah::whereId(@$wilayah->id)->update([
                'longitude' => @$row["longitude"],
                'latitude' => @$row["latitude"],
                'tahun_pendirian' => @$row["tahun_berdiri"],
                'tahun_pembubaran' => @$row["tahun_pembubaran"],
                'alamat_kantor_pemerintahan'=> @$row["alamat_kantor_pemerintahan"],
                'alamat_kantor_dprd'=> @$row["alamat_kantor_dprd"],
                'id_dataran' => intval(@$id[0])

            ]);
        }
    }
}