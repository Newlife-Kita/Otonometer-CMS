<?php

namespace App\Export;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;


class DprdDataExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithCustomValueBinder, FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $nilai =  collect(
            $results = DB::select('
        SELECT
            d.id_wilayah AS "Id Wilayah",
            mw.kode AS "Kode Wilayah",
            mw.nama AS "Nama Wilayah",
            d.tahun AS "Tahun",
            d.nama_lengkap AS "Nama Lengkap",
            SUBSTRING_INDEX(SUBSTRING_INDEX(mj.nama, \'"id":"\', -1), \'",\', 1) AS "Jabatan",
            SUBSTRING_INDEX(SUBSTRING_INDEX(mk.nama, \'"id":"\', -1), \'",\', 1) AS "Komisi",
            mp.nama AS "Partai",
            d.tahun_lantik AS "Tahun Lantik",
            d.tahun_akhir AS "Tahun Akhir"
        FROM
            dprd d
        LEFT JOIN
            master_jabatan mj ON d.id_jabatan = mj.id
        LEFT JOIN
            master_wilayah mw ON d.id_wilayah = mw.id
        LEFT JOIN
            master_komisi mk ON d.id_komisi = mk.id
        LEFT JOIN
            master_partai mp ON d.id_partai = mp.id
        WHERE
            d.id_jabatan NOT IN (7, 11, 23, 8, 12, 24)
    ')

            // Process $results as needed
        );

        $nilai->map(function ($item) {
            $item->{`Nama Wilayah`} = @json_decode($item->{`Nama Wilayah`}, true);

            return $item;
        });

        return $nilai;
    }

    public function headings(): array
    {
        return [
            "Id Wilayah",
            "Kode Wilayah",
            "Nama Wilayah",
            "Tahun",
            "Nama Lengkap",
            "Jabatan",
            "Komisi",
            "Partai",
            "Tahun Lantik",
            "Tahun Akhir"
        ];
    }
}
