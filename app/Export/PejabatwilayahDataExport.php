<?php

namespace App\Export;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PejabatwilayahDataExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithCustomValueBinder, FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $nilai = collect(DB::select("
        WITH Ketua AS (
            SELECT
                d.id_wilayah AS `Id Wilayah`,
                mw.kode AS `Kode Wilayah`,
                mw.nama AS `Nama Wilayah`,
                d.tahun AS `Tahun`,
                d.nama_lengkap AS Nama,
                d.tahun_lantik as tahun_lantik,
                d.tahun_akhir as tahun_akhir
            FROM
                wilayah_jabatan d
            LEFT JOIN
                master_wilayah mw ON d.id_wilayah = mw.id
            LEFT JOIN
                master_jabatan mj ON d.id_jabatan = mj.id
            WHERE
                 mj.id IN (1,3,13)
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
                d.tahun_akhir as tahun_akhir
            FROM
                wilayah_jabatan d
            LEFT JOIN
                master_wilayah mw ON d.id_wilayah = mw.id
            LEFT JOIN
                master_jabatan mj ON d.id_jabatan = mj.id
            WHERE
                mj.id IN (2,4,14)
                AND d.deleted_at IS NULL
        )
        SELECT
            k.`Id Wilayah` as `Id Wilayah`,
            k.`Kode Wilayah` as `Kode Wilayah`,
            k.`Nama Wilayah` as `Nama Wilayah`,
            k.tahun as `Tahun`,
            k.`Nama` as `Nama Ketua`,
            k.`tahun_lantik` as `Awal Masa Jabatan Ketua`,
            k.`tahun_akhir` as `Akhir Masa Jabatan Ketua`,
            wk.`Nama` as `Nama Wakil Ketua`,
            wk.`tahun_lantik` as `Awal Masa Jabatan Wakil`,
            wk.`tahun_akhir` as `Akhir Masa Jabatan Wakil`
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
            k.`Nama` as `Nama Ketua`,
            k.`tahun_lantik` as `Awal Masa Jabatan Ketua`,
            k.`tahun_akhir` as `Akhir Masa Jabatan Ketua`,
            wk.`Nama` as `Nama Wakil Ketua`,
            wk.`tahun_lantik` as `Awal Masa Jabatan Wakil`,
            wk.`tahun_akhir` as `Akhir Masa Jabatan Wakil`
        FROM
            Ketua k
        RIGHT JOIN
            Wakil_Ketua wk ON k.tahun = wk.tahun
            AND k.tahun_lantik = wk.tahun_lantik
            AND k.tahun_akhir = wk.tahun_akhir
            AND k.`Id Wilayah` = wk.`Id Wilayah`
        "));

        $nilai->map(function ($item) {
            $item->{`Nama Wilayah`} = @json_decode($item->{`Nama Wilayah`}, true);

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
            'Nama Ketua',
            'Awal Masa Jabatan Ketua',
            'Akhir Masa Jabatan Ketua',
            'Nama Wakil Ketua',
            'Awal Masa Jabatan Wakil',
            'Akhir Masa Jabatan Wakil'
        ];
    }
}
