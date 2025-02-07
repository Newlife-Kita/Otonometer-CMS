<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;
use illuminate\Support\Facades\DB;

class PermissionsLabelTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('permissions_label')->insertOrIgnore(array(
            0 =>
            array(
                'id' => 1,
                'name' => 'Permission Group',
                'permission_group_id' => 1,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => '2024-01-24 07:00:00',
            ),
            1 =>
            array(
                'id' => 2,
                'name' => 'Permission',
                'permission_group_id' => 1,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => '2024-01-24 07:00:00',
            ),
            2 =>
            array(
                'id' => 3,
                'name' => 'Roles',
                'permission_group_id' => 1,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            3 =>
            array(
                'id' => 4,
                'name' => 'Users',
                'permission_group_id' => 1,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            4 =>
            array(
                'id' => 5,
                'name' => 'Admin Wilayah',
                'permission_group_id' => 1,
                'created_at' => '2023-10-18 06:37:03',
                'updated_at' => '2023-10-18 06:37:03',
                'deleted_at' => NULL,
            ),
            5 =>
            array(
                'id' => 6,
                'name' => 'Dataran/Infografis',
                'permission_group_id' => 3,
                'created_at' => '2023-10-18 06:37:03',
                'updated_at' => '2023-10-19 06:46:28',
                'deleted_at' => NULL,
            ),
            6 =>
            array(
                'id' => 7,
                'name' => 'Wilayah',
                'permission_group_id' => 3,
                'created_at' => '2023-10-18 07:08:41',
                'updated_at' => '2023-10-18 07:08:41',
                'deleted_at' => NULL,
            ),
            7 =>
            array(
                'id' => 8,
                'name' => 'Satuan',
                'permission_group_id' => 3,
                'created_at' => '2023-10-18 09:26:50',
                'updated_at' => '2023-10-18 09:45:58',
                'deleted_at' => NULL,
            ),
            8 =>
            array(
                'id' => 9,
                'name' => 'Komisi',
                'permission_group_id' => 3,
                'created_at' => '2023-10-18 09:27:27',
                'updated_at' => '2023-10-18 09:27:27',
                'deleted_at' => NULL,
            ),
            9 =>
            array(
                'id' => 10,
                'name' => 'Sektor',
                'permission_group_id' => 3,
                'created_at' => '2023-10-18 09:27:59',
                'updated_at' => '2023-10-20 07:43:37',
                'deleted_at' => '2023-10-20 07:43:37',
            ),
            10 =>
            array(
                'id' => 11,
                'name' => 'Nilai',
                'permission_group_id' => 3,
                'created_at' => '2023-10-18 09:28:26',
                'updated_at' => '2023-10-20 07:43:02',
                'deleted_at' => '2023-10-20 07:43:02',
            ),
            11 =>
            array(
                'id' => 12,
                'name' => 'Jabatan',
                'permission_group_id' => 3,
                'created_at' => '2023-10-18 09:29:02',
                'updated_at' => '2023-10-18 09:29:02',
                'deleted_at' => NULL,
            ),
            12 =>
            array(
                'id' => 13,
                'name' => 'Bidang',
                'permission_group_id' => 3,
                'created_at' => '2023-10-18 09:29:31',
                'updated_at' => '2023-10-18 09:29:31',
                'deleted_at' => NULL,
            ),
            13 =>
            array(
                'id' => 14,
                'name' => 'Suku Dinas Pemda',
                'permission_group_id' => 5,
                'created_at' => '2023-10-18 09:32:19',
                'updated_at' => '2023-10-18 09:43:14',
                'deleted_at' => NULL,
            ),
            14 =>
            array(
                'id' => 15,
                'name' => 'Pejabat Suku Dinas',
                'permission_group_id' => 5,
                'created_at' => '2023-10-18 09:32:49',
                'updated_at' => '2023-10-18 09:44:08',
                'deleted_at' => NULL,
            ),
            15 =>
            array(
                'id' => 16,
                'name' => 'Data Wilayah',
                'permission_group_id' => 5,
                'created_at' => '2023-10-18 09:34:06',
                'updated_at' => '2023-10-20 07:41:07',
                'deleted_at' => NULL,
            ),
            16 =>
            array(
                'id' => 17,
                'name' => 'Pejabat Pemda',
                'permission_group_id' => 5,
                'created_at' => '2023-10-18 09:34:46',
                'updated_at' => '2023-10-18 09:43:40',
                'deleted_at' => NULL,
            ),
            17 =>
            array(
                'id' => 18,
                'name' => 'Sektor Nilai Rill',
                'permission_group_id' => 4,
                'created_at' => '2023-10-18 09:35:30',
                'updated_at' => '2023-10-20 07:43:57',
                'deleted_at' => '2023-10-20 07:43:57',
            ),
            18 =>
            array(
                'id' => 19,
                'name' => 'Sektor Nilai Perkapita',
                'permission_group_id' => 4,
                'created_at' => '2023-10-18 09:36:00',
                'updated_at' => '2023-10-20 07:43:47',
                'deleted_at' => '2023-10-20 07:43:47',
            ),
            19 =>
            array(
                'id' => 20,
                'name' => 'Bidang Nilai Perkapita',
                'permission_group_id' => 4,
                'created_at' => '2023-10-18 09:37:05',
                'updated_at' => '2023-10-18 09:41:50',
                'deleted_at' => '2024-01-24 07:00:00',
            ),
            20 =>
            array(
                'id' => 21,
                'name' => 'Bidang Nilai Rill',
                'permission_group_id' => 4,
                'created_at' => '2023-10-18 09:37:31',
                'updated_at' => '2023-10-18 09:41:24',
                'deleted_at' => '2024-01-24 07:00:00',
            ),
            21 =>
            array(
                'id' => 22,
                'name' => 'Ekonomi Daerah',
                'permission_group_id' => 3,
                'created_at' => '2023-10-19 06:44:28',
                'updated_at' => '2023-10-19 06:46:14',
                'deleted_at' => NULL,
            ),
            22 =>
            array(
                'id' => 23,
                'name' => 'Partai',
                'permission_group_id' => 3,
                'created_at' => '2023-10-20 06:55:38',
                'updated_at' => '2023-10-20 07:41:38',
                'deleted_at' => NULL,
            ),
            23 =>
            array(
                'id' => 24,
                'name' => 'Dprd',
                'permission_group_id' => 5,
                'created_at' => '2023-10-20 07:07:01',
                'updated_at' => '2023-10-20 07:41:50',
                'deleted_at' => NULL,
            ),
            24 =>
            array(
                'id' => 25,
                'name' => 'Bahasa',
                'permission_group_id' => 3,
                'created_at' => '2023-10-25 08:31:10',
                'updated_at' => '2023-10-25 08:51:40',
                'deleted_at' => '2024-01-24 07:00:00',
            ),
            25 =>
            array(
                'id' => 26,
                'name' => 'Kategori',
                'permission_group_id' => 3,
                'created_at' => '2023-10-25 08:34:49',
                'updated_at' => '2023-10-25 08:53:57',
                'deleted_at' => NULL,
            ),
            26 =>
            array(
                'id' => 27,
                'name' => 'Paket',
                'permission_group_id' => 2,
                'created_at' => '2023-10-25 08:37:05',
                'updated_at' => '2023-10-25 08:37:05',
                'deleted_at' => NULL,
            ),
            27 =>
            array(
                'id' => 28,
                'name' => 'Member',
                'permission_group_id' => 6,
                'created_at' => '2023-10-25 08:37:31',
                'updated_at' => '2023-10-25 08:52:38',
                'deleted_at' => NULL,
            ),
            28 =>
            array(
                'id' => 29,
                'name' => 'Paket Member',
                'permission_group_id' => 6,
                'created_at' => '2023-10-25 08:38:13',
                'updated_at' => '2023-10-25 08:53:21',
                'deleted_at' => NULL,
            ),
            29 =>
            array(
                'id' => 30,
                'name' => 'Aktifitas Member',
                'permission_group_id' => 6,
                'created_at' => '2023-10-25 08:40:53',
                'updated_at' => '2023-10-25 08:53:02',
                'deleted_at' => NULL,
            ),
            30 =>
            array(
                'id' => 31,
                'name' => 'Pekerjaan',
                'permission_group_id' => 2,
                'created_at' => '2023-11-06 14:50:47',
                'updated_at' => '2023-11-06 14:50:47',
                'deleted_at' => NULL,
            ),
            31 =>
            array(
                'id' => 32,
                'name' => 'Sumber Informasi',
                'permission_group_id' => 2,
                'created_at' => '2023-11-07 14:24:13',
                'updated_at' => '2023-11-07 14:24:13',
                'deleted_at' => NULL,
            ),
            32 =>
            array(
                'id' => 33,
                'name' => 'Setting',
                'permission_group_id' => 2,
                'created_at' => '2023-11-08 10:15:09',
                'updated_at' => '2023-11-08 10:15:09',
                'deleted_at' => '2024-01-24 07:00:00',
            ),
            33 =>
            array(
                'id' => 34,
                'name' => 'Bidang Nilai',
                'permission_group_id' => 4,
                'created_at' => '2023-11-08 15:29:27',
                'updated_at' => '2023-11-08 15:29:27',
                'deleted_at' => NULL,
            ),
            34 =>
            array(
                'id' => 35,
                'name' => 'Tahun Nomenklatur',
                'permission_group_id' => 4,
                'created_at' => '2023-11-16 14:26:01',
                'updated_at' => '2023-11-16 14:26:01',
                'deleted_at' => NULL,
            ),
            35 =>
            array(
                'id' => 36,
                'name' => 'Tahun Data',
                'permission_group_id' => 3,
                'created_at' => '2023-11-20 13:41:38',
                'updated_at' => '2023-11-20 13:45:14',
                'deleted_at' => NULL,
            ),
            36 =>
            array(
                'id' => 37,
                'name' => 'Catatan Bidang/Sektor',
                'permission_group_id' => 3,
                'created_at' => '2023-11-20 14:38:42',
                'updated_at' => '2023-11-20 14:38:42',
                'deleted_at' => NULL,
            ),
            37 =>
            array(
                'id' => 38,
                'name' => 'Kodepos',
                'permission_group_id' => 3,
                'created_at' => '2023-11-21 16:50:32',
                'updated_at' => '2023-11-21 16:50:32',
                'deleted_at' => NULL,
            ),
            38 =>
            array(
                'id' => 39,
                'name' => 'File Informasi',
                'permission_group_id' => 3,
                'created_at' => '2023-11-23 11:26:34',
                'updated_at' => '2023-11-23 11:26:34',
                'deleted_at' => NULL,
            ),
            39 =>
            array(
                'id' => 40,
                'name' => 'Sumber Data',
                'permission_group_id' => 3,
                'created_at' => '2023-12-03 16:14:36',
                'updated_at' => '2023-12-03 16:22:01',
                'deleted_at' => NULL,
            ),
            40 =>
            array(
                'id' => 43,
                'name' => 'Versi Aplikasi',
                'permission_group_id' => 7,
                'created_at' => '2024-03-01 16:31:17',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            41 =>
            array(
                'id' => 44,
                'name' => 'LogUpload',
                'permission_group_id' => 8,
                'created_at' => '2024-05-03 14:51:03',
                'updated_at' => '2024-05-03 14:51:03',
                'deleted_at' => NULL,
            ),
        ));
    }
}
