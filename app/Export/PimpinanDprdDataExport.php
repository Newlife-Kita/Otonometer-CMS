<?php

namespace App\Export;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PimpinanDprdDataExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithCustomValueBinder, FromCollection, WithHeadings
{
    public function collection()
    {
        $nilai =collect(DB::select("
        WITH Ketua AS (
            SELECT
                d.id_wilayah AS `Id Wilayah`,
                mw.kode AS `Kode Wilayah`,
                mw.nama AS `Nama Wilayah`,
                d.tahun AS `Tahun`,
                d.nama_lengkap AS Nama,
                d.tahun_lantik as tahun_lantik,
                d.tahun_akhir as tahun_akhir,
                mp.nama as nama_partai
            FROM
                dprd d
            LEFT JOIN
                master_wilayah mw ON d.id_wilayah = mw.id
            LEFT JOIN
                master_jabatan mj ON d.id_jabatan = mj.id
            LEFT JOIN
                master_partai mp ON d.id_partai = mp.id
            WHERE
                d.id_komisi IS NULL
                AND mj.id IN (7,11,23)
                AND d.deleted_at IS NULL
        ),
        Wakil_Ketua AS (
            SELECT
                d.id_wilayah AS `Id Wilayah`,
                mw.kode AS `Kode Wilayah`,
                mw.nama AS `Nama Wilayah`,
                d.tahun AS `Tahun`,
                d.nama_lengkap AS Nama,
                d.tahun_lantik as tahun_lantik,
                d.tahun_akhir as tahun_akhir,
                mp.nama as nama_partai
            FROM
                dprd d
            LEFT JOIN
                master_wilayah mw ON d.id_wilayah = mw.id
            LEFT JOIN
                master_jabatan mj ON d.id_jabatan = mj.id
            LEFT JOIN
                master_partai mp ON d.id_partai = mp.id
            WHERE
                d.id_komisi IS NULL
                AND mj.id IN (8,12,24)
                AND d.deleted_at IS NULL
        )
        SELECT
            k.`Id Wilayah` as `Id Wilayah`,
            k.`Kode Wilayah` as `Kode Wilayah`,
            k.`Nama Wilayah` as `Nama Wilayah`,
            k.tahun as `Tahun`,
            k.`Nama` as `Nama Ketua DPRD`,
            k.`tahun_lantik` as `Awal Masa Jabatan Ketua`,
            k.`tahun_akhir` as `Akhir Masa Jabatan Ketua`,
            k.`nama_partai` as `Partai Ketua DPRD`,
            wk.`Nama` as `Nama Wakil Ketua DPRD`,
            wk.`tahun_lantik` as `Awal Masa Jabatan Wakil`,
            wk.`tahun_akhir` as `Akhir Masa Jabatan Wakil`,
            wk.`nama_partai` as `Partai Wakil Ketua DPRD`
        FROM
            Ketua k
        LEFT JOIN
            Wakil_Ketua wk ON k.tahun = wk.tahun
            AND k.tahun_lantik = wk.tahun_lantik
            AND k.tahun_akhir = wk.tahun_akhir
            AND k.`Id Wilayah` = wk.`Id Wilayah`
        UNION
        SELECT
            wk.`Id Wilayah` as `Id Wilayah`,
            wk.`Kode Wilayah` as `Kode Wilayah`,
            wk.`Nama Wilayah` as `Nama Wilayah`,
            wk.tahun as `Tahun`,
            k.`Nama` as `Nama Ketua DPRD`,
            k.`tahun_lantik` as `Awal Masa Jabatan Ketua`,
            k.`tahun_akhir` as `Akhir Masa Jabatan Ketua`,
            k.`nama_partai` as `Partai Ketua DPRD`,
            wk.`Nama` as `Nama Wakil Ketua DPRD`,
            wk.`tahun_lantik` as `Awal Masa Jabatan Wakil`,
            wk.`tahun_akhir` as `Akhir Masa Jabatan Wakil`,
            wk.`nama_partai` as `Partai Wakil Ketua DPRD`
        FROM
            Ketua k
        RIGHT JOIN
            Wakil_Ketua wk ON k.tahun = wk.tahun
            AND k.tahun_lantik = wk.tahun_lantik
            AND k.tahun_akhir = wk.tahun_akhir
            AND k.`Id Wilayah` = wk.`Id Wilayah`
        "));



        $nilai->map(function ($item) {
            $item->{'Nama Wilayah'} = @json_decode($item->{'Nama Wilayah'}, true)['id'];


            return $item;
        });

        return $nilai;
    }

    public function headings(): array
    {
        return [
            'Id Wilayah',
            'Kode Wilayah',
            'Nama Wilayah',
            'Tahun',
            'Nama Ketua DPRD',
            'Awal Masa Jabatan Ketua',
            'Akhir Masa Jabatan Ketua',
            'Partai Ketua DPRD',
            'Nama Wakil Ketua DPRD',
            'Awal Masa Jabatan Wakil',
            'Akhir Masa Jabatan Wakil',
            'Partai Wakil Ketua DPRD'
        ];
    }
}
