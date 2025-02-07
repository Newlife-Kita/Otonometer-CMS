<?php

namespace App\Import;

use App\Models\SudinTemp;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SudinImport implements ToCollection, WithHeadingRow
{
    private $wilayah;
    public function setWilayah($wilayah)
    {
        $this->wilayah = $wilayah;
    }
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            SudinTemp::updateOrCreate([
                'id_wilayah' => $this->wilayah->id,
                'nama_sudin' => $row['nama_sudin'],
            ], [
                'alamat' => $row['alamat'],
                'telp_pic' => $row['hpmobile_pic'],
                'nama_pic' => $row['nama_pic'],
                'email_pic' => $row['email_pic']
            ]);
        }
    }
}
