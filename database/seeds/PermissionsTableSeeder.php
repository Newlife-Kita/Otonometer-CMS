<?php
namespace Database\Seeds;

use Illuminate\Database\Seeder;
use illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('permissions')->insertOrIgnore(array(
            0 =>
            array(
                'id' => 1,
                'name' => 'permissiongroup-create',
                'guard_name' => 'web',
                'permissions_label_id' => 1,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 =>
            array(
                'id' => 2,
                'name' => 'permissiongroup-show',
                'guard_name' => 'web',
                'permissions_label_id' => 1,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            2 =>
            array(
                'id' => 3,
                'name' => 'permissiongroup-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 1,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            3 =>
            array(
                'id' => 4,
                'name' => 'permissiongroup-update',
                'guard_name' => 'web',
                'permissions_label_id' => 1,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            4 =>
            array(
                'id' => 5,
                'name' => 'permissiongroup-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 1,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            5 =>
            array(
                'id' => 6,
                'name' => 'permissiongroup-store',
                'guard_name' => 'web',
                'permissions_label_id' => 1,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            6 =>
            array(
                'id' => 7,
                'name' => 'permission-create',
                'guard_name' => 'web',
                'permissions_label_id' => 2,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            7 =>
            array(
                'id' => 8,
                'name' => 'permission-show',
                'guard_name' => 'web',
                'permissions_label_id' => 2,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            8 =>
            array(
                'id' => 9,
                'name' => 'permission-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 2,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            9 =>
            array(
                'id' => 10,
                'name' => 'permission-update',
                'guard_name' => 'web',
                'permissions_label_id' => 2,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            10 =>
            array(
                'id' => 11,
                'name' => 'permission-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 2,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            11 =>
            array(
                'id' => 12,
                'name' => 'permission-store',
                'guard_name' => 'web',
                'permissions_label_id' => 2,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            12 =>
            array(
                'id' => 13,
                'name' => 'role-create',
                'guard_name' => 'web',
                'permissions_label_id' => 3,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            13 =>
            array(
                'id' => 14,
                'name' => 'role-show',
                'guard_name' => 'web',
                'permissions_label_id' => 3,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            14 =>
            array(
                'id' => 15,
                'name' => 'role-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 3,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            15 =>
            array(
                'id' => 16,
                'name' => 'role-update',
                'guard_name' => 'web',
                'permissions_label_id' => 3,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            16 =>
            array(
                'id' => 17,
                'name' => 'role-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 3,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            17 =>
            array(
                'id' => 18,
                'name' => 'role-store',
                'guard_name' => 'web',
                'permissions_label_id' => 3,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => '2023-10-16 12:03:46',
            ),
            18 =>
            array(
                'id' => 19,
                'name' => 'user-create',
                'guard_name' => 'web',
                'permissions_label_id' => 4,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            19 =>
            array(
                'id' => 20,
                'name' => 'user-show',
                'guard_name' => 'web',
                'permissions_label_id' => 4,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            20 =>
            array(
                'id' => 21,
                'name' => 'user-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 4,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            21 =>
            array(
                'id' => 22,
                'name' => 'user-update',
                'guard_name' => 'web',
                'permissions_label_id' => 4,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            22 =>
            array(
                'id' => 23,
                'name' => 'user-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 4,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            23 =>
            array(
                'id' => 24,
                'name' => 'user-store',
                'guard_name' => 'web',
                'permissions_label_id' => 4,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            24 =>
            array(
                'id' => 25,
                'name' => 'dataran-create',
                'guard_name' => 'web',
                'permissions_label_id' => 6,
                'created_at' => '2023-10-18 06:37:04',
                'updated_at' => '2023-10-18 06:37:04',
                'deleted_at' => NULL,
            ),
            25 =>
            array(
                'id' => 26,
                'name' => 'dataran-show',
                'guard_name' => 'web',
                'permissions_label_id' => 6,
                'created_at' => '2023-10-18 06:37:04',
                'updated_at' => '2023-10-18 06:37:04',
                'deleted_at' => NULL,
            ),
            26 =>
            array(
                'id' => 27,
                'name' => 'dataran-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 6,
                'created_at' => '2023-10-18 06:37:04',
                'updated_at' => '2023-10-18 06:37:04',
                'deleted_at' => NULL,
            ),
            27 =>
            array(
                'id' => 28,
                'name' => 'dataran-update',
                'guard_name' => 'web',
                'permissions_label_id' => 6,
                'created_at' => '2023-10-18 06:37:04',
                'updated_at' => '2023-10-18 06:37:04',
                'deleted_at' => NULL,
            ),
            28 =>
            array(
                'id' => 29,
                'name' => 'dataran-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 6,
                'created_at' => '2023-10-18 06:37:04',
                'updated_at' => '2023-10-18 06:37:04',
                'deleted_at' => NULL,
            ),
            29 =>
            array(
                'id' => 30,
                'name' => 'dataran-store',
                'guard_name' => 'web',
                'permissions_label_id' => 6,
                'created_at' => '2023-10-18 06:37:04',
                'updated_at' => '2023-10-18 06:37:04',
                'deleted_at' => NULL,
            ),
            30 =>
            array(
                'id' => 31,
                'name' => 'admin-create',
                'guard_name' => 'web',
                'permissions_label_id' => 5,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            31 =>
            array(
                'id' => 32,
                'name' => 'admin-show',
                'guard_name' => 'web',
                'permissions_label_id' => 5,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            32 =>
            array(
                'id' => 33,
                'name' => 'admin-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 5,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            33 =>
            array(
                'id' => 34,
                'name' => 'admin-update',
                'guard_name' => 'web',
                'permissions_label_id' => 5,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            34 =>
            array(
                'id' => 35,
                'name' => 'admin-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 5,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            35 =>
            array(
                'id' => 36,
                'name' => 'admin-store',
                'guard_name' => 'web',
                'permissions_label_id' => 5,
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            36 =>
            array(
                'id' => 38,
                'name' => 'wilayah-create',
                'guard_name' => 'web',
                'permissions_label_id' => 7,
                'created_at' => '2023-10-18 07:08:41',
                'updated_at' => '2023-10-18 07:08:41',
                'deleted_at' => NULL,
            ),
            37 =>
            array(
                'id' => 39,
                'name' => 'wilayah-show',
                'guard_name' => 'web',
                'permissions_label_id' => 7,
                'created_at' => '2023-10-18 07:08:41',
                'updated_at' => '2023-10-18 07:08:41',
                'deleted_at' => NULL,
            ),
            38 =>
            array(
                'id' => 40,
                'name' => 'wilayah-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 7,
                'created_at' => '2023-10-18 07:08:41',
                'updated_at' => '2023-10-18 07:08:41',
                'deleted_at' => NULL,
            ),
            39 =>
            array(
                'id' => 41,
                'name' => 'wilayah-update',
                'guard_name' => 'web',
                'permissions_label_id' => 7,
                'created_at' => '2023-10-18 07:08:41',
                'updated_at' => '2023-10-18 07:08:41',
                'deleted_at' => NULL,
            ),
            40 =>
            array(
                'id' => 42,
                'name' => 'wilayah-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 7,
                'created_at' => '2023-10-18 07:08:41',
                'updated_at' => '2023-10-18 07:08:41',
                'deleted_at' => NULL,
            ),
            41 =>
            array(
                'id' => 43,
                'name' => 'wilayah-store',
                'guard_name' => 'web',
                'permissions_label_id' => 7,
                'created_at' => '2023-10-18 07:08:41',
                'updated_at' => '2023-10-18 07:08:41',
                'deleted_at' => NULL,
            ),
            42 =>
            array(
                'id' => 44,
                'name' => 'satuan-create',
                'guard_name' => 'web',
                'permissions_label_id' => 8,
                'created_at' => '2023-10-18 09:26:50',
                'updated_at' => '2023-10-18 09:26:50',
                'deleted_at' => NULL,
            ),
            43 =>
            array(
                'id' => 45,
                'name' => 'satuan-show',
                'guard_name' => 'web',
                'permissions_label_id' => 8,
                'created_at' => '2023-10-18 09:26:50',
                'updated_at' => '2023-10-18 09:26:50',
                'deleted_at' => NULL,
            ),
            44 =>
            array(
                'id' => 46,
                'name' => 'satuan-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 8,
                'created_at' => '2023-10-18 09:26:51',
                'updated_at' => '2023-10-18 09:26:51',
                'deleted_at' => NULL,
            ),
            45 =>
            array(
                'id' => 47,
                'name' => 'satuan-update',
                'guard_name' => 'web',
                'permissions_label_id' => 8,
                'created_at' => '2023-10-18 09:26:51',
                'updated_at' => '2023-10-18 09:26:51',
                'deleted_at' => NULL,
            ),
            46 =>
            array(
                'id' => 48,
                'name' => 'satuan-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 8,
                'created_at' => '2023-10-18 09:26:51',
                'updated_at' => '2023-10-18 09:26:51',
                'deleted_at' => NULL,
            ),
            47 =>
            array(
                'id' => 49,
                'name' => 'satuan-store',
                'guard_name' => 'web',
                'permissions_label_id' => 8,
                'created_at' => '2023-10-18 09:26:51',
                'updated_at' => '2023-10-18 09:26:51',
                'deleted_at' => NULL,
            ),
            48 =>
            array(
                'id' => 50,
                'name' => 'komisi-create',
                'guard_name' => 'web',
                'permissions_label_id' => 9,
                'created_at' => '2023-10-18 09:27:27',
                'updated_at' => '2023-10-18 09:27:27',
                'deleted_at' => NULL,
            ),
            49 =>
            array(
                'id' => 51,
                'name' => 'komisi-show',
                'guard_name' => 'web',
                'permissions_label_id' => 9,
                'created_at' => '2023-10-18 09:27:27',
                'updated_at' => '2023-10-18 09:27:27',
                'deleted_at' => NULL,
            ),
            50 =>
            array(
                'id' => 52,
                'name' => 'komisi-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 9,
                'created_at' => '2023-10-18 09:27:27',
                'updated_at' => '2023-10-18 09:27:27',
                'deleted_at' => NULL,
            ),
            51 =>
            array(
                'id' => 53,
                'name' => 'komisi-update',
                'guard_name' => 'web',
                'permissions_label_id' => 9,
                'created_at' => '2023-10-18 09:27:27',
                'updated_at' => '2023-10-18 09:27:27',
                'deleted_at' => NULL,
            ),
            52 =>
            array(
                'id' => 54,
                'name' => 'komisi-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 9,
                'created_at' => '2023-10-18 09:27:27',
                'updated_at' => '2023-10-18 09:27:27',
                'deleted_at' => NULL,
            ),
            53 =>
            array(
                'id' => 55,
                'name' => 'komisi-store',
                'guard_name' => 'web',
                'permissions_label_id' => 9,
                'created_at' => '2023-10-18 09:27:27',
                'updated_at' => '2023-10-18 09:27:27',
                'deleted_at' => NULL,
            ),
            54 =>
            array(
                'id' => 68,
                'name' => 'jabatan-create',
                'guard_name' => 'web',
                'permissions_label_id' => 12,
                'created_at' => '2023-10-18 09:29:02',
                'updated_at' => '2023-10-18 09:29:02',
                'deleted_at' => NULL,
            ),
            55 =>
            array(
                'id' => 69,
                'name' => 'jabatan-show',
                'guard_name' => 'web',
                'permissions_label_id' => 12,
                'created_at' => '2023-10-18 09:29:02',
                'updated_at' => '2023-10-18 09:29:02',
                'deleted_at' => NULL,
            ),
            56 =>
            array(
                'id' => 70,
                'name' => 'jabatan-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 12,
                'created_at' => '2023-10-18 09:29:02',
                'updated_at' => '2023-10-18 09:29:02',
                'deleted_at' => NULL,
            ),
            57 =>
            array(
                'id' => 71,
                'name' => 'jabatan-update',
                'guard_name' => 'web',
                'permissions_label_id' => 12,
                'created_at' => '2023-10-18 09:29:02',
                'updated_at' => '2023-10-18 09:29:02',
                'deleted_at' => NULL,
            ),
            58 =>
            array(
                'id' => 72,
                'name' => 'jabatan-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 12,
                'created_at' => '2023-10-18 09:29:02',
                'updated_at' => '2023-10-18 09:29:02',
                'deleted_at' => NULL,
            ),
            59 =>
            array(
                'id' => 73,
                'name' => 'jabatan-store',
                'guard_name' => 'web',
                'permissions_label_id' => 12,
                'created_at' => '2023-10-18 09:29:02',
                'updated_at' => '2023-10-18 09:29:02',
                'deleted_at' => NULL,
            ),
            60 =>
            array(
                'id' => 74,
                'name' => 'bidang-create',
                'guard_name' => 'web',
                'permissions_label_id' => 13,
                'created_at' => '2023-10-18 09:29:31',
                'updated_at' => '2023-10-18 09:29:31',
                'deleted_at' => NULL,
            ),
            61 =>
            array(
                'id' => 75,
                'name' => 'bidang-show',
                'guard_name' => 'web',
                'permissions_label_id' => 13,
                'created_at' => '2023-10-18 09:29:31',
                'updated_at' => '2023-10-18 09:29:31',
                'deleted_at' => NULL,
            ),
            62 =>
            array(
                'id' => 76,
                'name' => 'bidang-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 13,
                'created_at' => '2023-10-18 09:29:31',
                'updated_at' => '2023-10-18 09:29:31',
                'deleted_at' => NULL,
            ),
            63 =>
            array(
                'id' => 77,
                'name' => 'bidang-update',
                'guard_name' => 'web',
                'permissions_label_id' => 13,
                'created_at' => '2023-10-18 09:29:31',
                'updated_at' => '2023-10-18 09:29:31',
                'deleted_at' => NULL,
            ),
            64 =>
            array(
                'id' => 78,
                'name' => 'bidang-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 13,
                'created_at' => '2023-10-18 09:29:31',
                'updated_at' => '2023-10-18 09:29:31',
                'deleted_at' => NULL,
            ),
            65 =>
            array(
                'id' => 79,
                'name' => 'bidang-store',
                'guard_name' => 'web',
                'permissions_label_id' => 13,
                'created_at' => '2023-10-18 09:29:31',
                'updated_at' => '2023-10-18 09:29:31',
                'deleted_at' => NULL,
            ),
            66 =>
            array(
                'id' => 80,
                'name' => 'sudin-create',
                'guard_name' => 'web',
                'permissions_label_id' => 14,
                'created_at' => '2023-10-18 09:32:19',
                'updated_at' => '2023-10-18 09:32:19',
                'deleted_at' => NULL,
            ),
            67 =>
            array(
                'id' => 81,
                'name' => 'sudin-show',
                'guard_name' => 'web',
                'permissions_label_id' => 14,
                'created_at' => '2023-10-18 09:32:19',
                'updated_at' => '2023-10-18 09:32:19',
                'deleted_at' => NULL,
            ),
            68 =>
            array(
                'id' => 82,
                'name' => 'sudin-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 14,
                'created_at' => '2023-10-18 09:32:19',
                'updated_at' => '2023-10-18 09:32:19',
                'deleted_at' => NULL,
            ),
            69 =>
            array(
                'id' => 83,
                'name' => 'sudin-update',
                'guard_name' => 'web',
                'permissions_label_id' => 14,
                'created_at' => '2023-10-18 09:32:19',
                'updated_at' => '2023-10-18 09:32:19',
                'deleted_at' => NULL,
            ),
            70 =>
            array(
                'id' => 84,
                'name' => 'sudin-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 14,
                'created_at' => '2023-10-18 09:32:19',
                'updated_at' => '2023-10-18 09:32:19',
                'deleted_at' => NULL,
            ),
            71 =>
            array(
                'id' => 85,
                'name' => 'sudin-store',
                'guard_name' => 'web',
                'permissions_label_id' => 14,
                'created_at' => '2023-10-18 09:32:19',
                'updated_at' => '2023-10-18 09:32:19',
                'deleted_at' => NULL,
            ),
            72 =>
            array(
                'id' => 86,
                'name' => 'pejabatsudin-create',
                'guard_name' => 'web',
                'permissions_label_id' => 15,
                'created_at' => '2023-10-18 09:32:49',
                'updated_at' => '2023-10-18 09:32:49',
                'deleted_at' => NULL,
            ),
            73 =>
            array(
                'id' => 87,
                'name' => 'pejabatsudin-show',
                'guard_name' => 'web',
                'permissions_label_id' => 15,
                'created_at' => '2023-10-18 09:32:49',
                'updated_at' => '2023-10-18 09:32:49',
                'deleted_at' => NULL,
            ),
            74 =>
            array(
                'id' => 88,
                'name' => 'pejabatsudin-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 15,
                'created_at' => '2023-10-18 09:32:49',
                'updated_at' => '2023-10-18 09:32:49',
                'deleted_at' => NULL,
            ),
            75 =>
            array(
                'id' => 89,
                'name' => 'pejabatsudin-update',
                'guard_name' => 'web',
                'permissions_label_id' => 15,
                'created_at' => '2023-10-18 09:32:49',
                'updated_at' => '2023-10-18 09:32:49',
                'deleted_at' => NULL,
            ),
            76 =>
            array(
                'id' => 90,
                'name' => 'pejabatsudin-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 15,
                'created_at' => '2023-10-18 09:32:49',
                'updated_at' => '2023-10-18 09:32:49',
                'deleted_at' => NULL,
            ),
            77 =>
            array(
                'id' => 91,
                'name' => 'pejabatsudin-store',
                'guard_name' => 'web',
                'permissions_label_id' => 15,
                'created_at' => '2023-10-18 09:32:49',
                'updated_at' => '2023-10-18 09:32:49',
                'deleted_at' => NULL,
            ),
            78 =>
            array(
                'id' => 92,
                'name' => 'datawilayah-create',
                'guard_name' => 'web',
                'permissions_label_id' => 16,
                'created_at' => '2023-10-18 09:34:06',
                'updated_at' => '2023-10-18 09:34:06',
                'deleted_at' => NULL,
            ),
            79 =>
            array(
                'id' => 93,
                'name' => 'datawilayah-show',
                'guard_name' => 'web',
                'permissions_label_id' => 16,
                'created_at' => '2023-10-18 09:34:06',
                'updated_at' => '2023-10-18 09:34:06',
                'deleted_at' => NULL,
            ),
            80 =>
            array(
                'id' => 94,
                'name' => 'datawilayah-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 16,
                'created_at' => '2023-10-18 09:34:06',
                'updated_at' => '2023-10-18 09:34:06',
                'deleted_at' => NULL,
            ),
            81 =>
            array(
                'id' => 95,
                'name' => 'datawilayah-update',
                'guard_name' => 'web',
                'permissions_label_id' => 16,
                'created_at' => '2023-10-18 09:34:06',
                'updated_at' => '2023-10-18 09:34:06',
                'deleted_at' => NULL,
            ),
            82 =>
            array(
                'id' => 96,
                'name' => 'datawilayah-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 16,
                'created_at' => '2023-10-18 09:34:06',
                'updated_at' => '2023-10-18 09:34:06',
                'deleted_at' => NULL,
            ),
            83 =>
            array(
                'id' => 97,
                'name' => 'datawilayah-store',
                'guard_name' => 'web',
                'permissions_label_id' => 16,
                'created_at' => '2023-10-18 09:34:06',
                'updated_at' => '2023-10-18 09:34:06',
                'deleted_at' => NULL,
            ),
            84 =>
            array(
                'id' => 98,
                'name' => 'pejabatwilayah-create',
                'guard_name' => 'web',
                'permissions_label_id' => 17,
                'created_at' => '2023-10-18 09:34:47',
                'updated_at' => '2023-10-18 09:34:47',
                'deleted_at' => NULL,
            ),
            85 =>
            array(
                'id' => 99,
                'name' => 'pejabatwilayah-show',
                'guard_name' => 'web',
                'permissions_label_id' => 17,
                'created_at' => '2023-10-18 09:34:47',
                'updated_at' => '2023-10-18 09:34:47',
                'deleted_at' => NULL,
            ),
            86 =>
            array(
                'id' => 100,
                'name' => 'pejabatwilayah-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 17,
                'created_at' => '2023-10-18 09:34:47',
                'updated_at' => '2023-10-18 09:34:47',
                'deleted_at' => NULL,
            ),
            87 =>
            array(
                'id' => 101,
                'name' => 'pejabatwilayah-update',
                'guard_name' => 'web',
                'permissions_label_id' => 17,
                'created_at' => '2023-10-18 09:34:47',
                'updated_at' => '2023-10-18 09:34:47',
                'deleted_at' => NULL,
            ),
            88 =>
            array(
                'id' => 102,
                'name' => 'pejabatwilayah-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 17,
                'created_at' => '2023-10-18 09:34:47',
                'updated_at' => '2023-10-18 09:34:47',
                'deleted_at' => NULL,
            ),
            89 =>
            array(
                'id' => 103,
                'name' => 'pejabatwilayah-store',
                'guard_name' => 'web',
                'permissions_label_id' => 17,
                'created_at' => '2023-10-18 09:34:47',
                'updated_at' => '2023-10-18 09:34:47',
                'deleted_at' => NULL,
            ),
            90 =>
            array(
                'id' => 116,
                'name' => 'bidangperkapita-create',
                'guard_name' => 'web',
                'permissions_label_id' => 20,
                'created_at' => '2023-10-18 09:37:05',
                'updated_at' => '2023-10-18 09:37:05',
                'deleted_at' => NULL,
            ),
            91 =>
            array(
                'id' => 117,
                'name' => 'bidangperkapita-show',
                'guard_name' => 'web',
                'permissions_label_id' => 20,
                'created_at' => '2023-10-18 09:37:05',
                'updated_at' => '2023-10-18 09:37:05',
                'deleted_at' => NULL,
            ),
            92 =>
            array(
                'id' => 118,
                'name' => 'bidangperkapita-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 20,
                'created_at' => '2023-10-18 09:37:05',
                'updated_at' => '2023-10-18 09:37:05',
                'deleted_at' => NULL,
            ),
            93 =>
            array(
                'id' => 119,
                'name' => 'bidangperkapita-update',
                'guard_name' => 'web',
                'permissions_label_id' => 20,
                'created_at' => '2023-10-18 09:37:05',
                'updated_at' => '2023-10-18 09:37:05',
                'deleted_at' => NULL,
            ),
            94 =>
            array(
                'id' => 120,
                'name' => 'bidangperkapita-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 20,
                'created_at' => '2023-10-18 09:37:05',
                'updated_at' => '2023-10-18 09:37:05',
                'deleted_at' => NULL,
            ),
            95 =>
            array(
                'id' => 121,
                'name' => 'bidangperkapita-store',
                'guard_name' => 'web',
                'permissions_label_id' => 20,
                'created_at' => '2023-10-18 09:37:05',
                'updated_at' => '2023-10-18 09:37:05',
                'deleted_at' => NULL,
            ),
            96 =>
            array(
                'id' => 122,
                'name' => 'bidangrill-create',
                'guard_name' => 'web',
                'permissions_label_id' => 21,
                'created_at' => '2023-10-18 09:37:31',
                'updated_at' => '2023-10-18 09:37:31',
                'deleted_at' => NULL,
            ),
            97 =>
            array(
                'id' => 123,
                'name' => 'bidangrill-show',
                'guard_name' => 'web',
                'permissions_label_id' => 21,
                'created_at' => '2023-10-18 09:37:31',
                'updated_at' => '2023-10-18 09:37:31',
                'deleted_at' => NULL,
            ),
            98 =>
            array(
                'id' => 124,
                'name' => 'bidangrill-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 21,
                'created_at' => '2023-10-18 09:37:31',
                'updated_at' => '2023-10-18 09:37:31',
                'deleted_at' => NULL,
            ),
            99 =>
            array(
                'id' => 125,
                'name' => 'bidangrill-update',
                'guard_name' => 'web',
                'permissions_label_id' => 21,
                'created_at' => '2023-10-18 09:37:31',
                'updated_at' => '2023-10-18 09:37:31',
                'deleted_at' => NULL,
            ),
            100 =>
            array(
                'id' => 126,
                'name' => 'bidangrill-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 21,
                'created_at' => '2023-10-18 09:37:31',
                'updated_at' => '2023-10-18 09:37:31',
                'deleted_at' => NULL,
            ),
            101 =>
            array(
                'id' => 127,
                'name' => 'bidangrill-store',
                'guard_name' => 'web',
                'permissions_label_id' => 21,
                'created_at' => '2023-10-18 09:37:31',
                'updated_at' => '2023-10-18 09:37:31',
                'deleted_at' => NULL,
            ),
            102 =>
            array(
                'id' => 128,
                'name' => 'ekonomi-create',
                'guard_name' => 'web',
                'permissions_label_id' => 22,
                'created_at' => '2023-10-19 06:44:28',
                'updated_at' => '2023-10-19 06:44:28',
                'deleted_at' => NULL,
            ),
            103 =>
            array(
                'id' => 129,
                'name' => 'ekonomi-show',
                'guard_name' => 'web',
                'permissions_label_id' => 22,
                'created_at' => '2023-10-19 06:44:28',
                'updated_at' => '2023-10-19 06:44:28',
                'deleted_at' => NULL,
            ),
            104 =>
            array(
                'id' => 130,
                'name' => 'ekonomi-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 22,
                'created_at' => '2023-10-19 06:44:28',
                'updated_at' => '2023-10-19 06:44:28',
                'deleted_at' => NULL,
            ),
            105 =>
            array(
                'id' => 131,
                'name' => 'ekonomi-update',
                'guard_name' => 'web',
                'permissions_label_id' => 22,
                'created_at' => '2023-10-19 06:44:28',
                'updated_at' => '2023-10-19 06:44:28',
                'deleted_at' => NULL,
            ),
            106 =>
            array(
                'id' => 132,
                'name' => 'ekonomi-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 22,
                'created_at' => '2023-10-19 06:44:28',
                'updated_at' => '2023-10-19 06:44:28',
                'deleted_at' => NULL,
            ),
            107 =>
            array(
                'id' => 133,
                'name' => 'ekonomi-store',
                'guard_name' => 'web',
                'permissions_label_id' => 22,
                'created_at' => '2023-10-19 06:44:29',
                'updated_at' => '2023-10-19 06:44:29',
                'deleted_at' => NULL,
            ),
            108 =>
            array(
                'id' => 134,
                'name' => 'partai-create',
                'guard_name' => 'web',
                'permissions_label_id' => 23,
                'created_at' => '2023-10-20 06:55:38',
                'updated_at' => '2023-10-20 06:55:38',
                'deleted_at' => NULL,
            ),
            109 =>
            array(
                'id' => 135,
                'name' => 'partai-show',
                'guard_name' => 'web',
                'permissions_label_id' => 23,
                'created_at' => '2023-10-20 06:55:38',
                'updated_at' => '2023-10-20 06:55:38',
                'deleted_at' => NULL,
            ),
            110 =>
            array(
                'id' => 136,
                'name' => 'partai-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 23,
                'created_at' => '2023-10-20 06:55:38',
                'updated_at' => '2023-10-20 06:55:38',
                'deleted_at' => NULL,
            ),
            111 =>
            array(
                'id' => 137,
                'name' => 'partai-update',
                'guard_name' => 'web',
                'permissions_label_id' => 23,
                'created_at' => '2023-10-20 06:55:38',
                'updated_at' => '2023-10-20 06:55:38',
                'deleted_at' => NULL,
            ),
            112 =>
            array(
                'id' => 138,
                'name' => 'partai-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 23,
                'created_at' => '2023-10-20 06:55:38',
                'updated_at' => '2023-10-20 06:55:38',
                'deleted_at' => NULL,
            ),
            113 =>
            array(
                'id' => 139,
                'name' => 'partai-store',
                'guard_name' => 'web',
                'permissions_label_id' => 23,
                'created_at' => '2023-10-20 06:55:38',
                'updated_at' => '2023-10-20 06:55:38',
                'deleted_at' => NULL,
            ),
            114 =>
            array(
                'id' => 140,
                'name' => 'dprd-create',
                'guard_name' => 'web',
                'permissions_label_id' => 24,
                'created_at' => '2023-10-20 07:07:01',
                'updated_at' => '2023-10-20 07:07:01',
                'deleted_at' => NULL,
            ),
            115 =>
            array(
                'id' => 141,
                'name' => 'dprd-show',
                'guard_name' => 'web',
                'permissions_label_id' => 24,
                'created_at' => '2023-10-20 07:07:01',
                'updated_at' => '2023-10-20 07:07:01',
                'deleted_at' => NULL,
            ),
            116 =>
            array(
                'id' => 142,
                'name' => 'dprd-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 24,
                'created_at' => '2023-10-20 07:07:01',
                'updated_at' => '2023-10-20 07:07:01',
                'deleted_at' => NULL,
            ),
            117 =>
            array(
                'id' => 143,
                'name' => 'dprd-update',
                'guard_name' => 'web',
                'permissions_label_id' => 24,
                'created_at' => '2023-10-20 07:07:01',
                'updated_at' => '2023-10-20 07:07:01',
                'deleted_at' => NULL,
            ),
            118 =>
            array(
                'id' => 144,
                'name' => 'dprd-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 24,
                'created_at' => '2023-10-20 07:07:02',
                'updated_at' => '2023-10-20 07:07:02',
                'deleted_at' => NULL,
            ),
            119 =>
            array(
                'id' => 145,
                'name' => 'dprd-store',
                'guard_name' => 'web',
                'permissions_label_id' => 24,
                'created_at' => '2023-10-20 07:07:02',
                'updated_at' => '2023-10-20 07:07:02',
                'deleted_at' => NULL,
            ),
            120 =>
            array(
                'id' => 146,
                'name' => 'bahasa-create',
                'guard_name' => 'web',
                'permissions_label_id' => 25,
                'created_at' => '2023-10-25 08:31:10',
                'updated_at' => '2023-10-25 08:31:10',
                'deleted_at' => NULL,
            ),
            121 =>
            array(
                'id' => 147,
                'name' => 'bahasa-show',
                'guard_name' => 'web',
                'permissions_label_id' => 25,
                'created_at' => '2023-10-25 08:31:10',
                'updated_at' => '2023-10-25 08:31:10',
                'deleted_at' => NULL,
            ),
            122 =>
            array(
                'id' => 148,
                'name' => 'bahasa-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 25,
                'created_at' => '2023-10-25 08:31:10',
                'updated_at' => '2023-10-25 08:31:10',
                'deleted_at' => NULL,
            ),
            123 =>
            array(
                'id' => 149,
                'name' => 'bahasa-update',
                'guard_name' => 'web',
                'permissions_label_id' => 25,
                'created_at' => '2023-10-25 08:31:10',
                'updated_at' => '2023-10-25 08:31:10',
                'deleted_at' => NULL,
            ),
            124 =>
            array(
                'id' => 150,
                'name' => 'bahasa-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 25,
                'created_at' => '2023-10-25 08:31:10',
                'updated_at' => '2023-10-25 08:31:10',
                'deleted_at' => NULL,
            ),
            125 =>
            array(
                'id' => 151,
                'name' => 'bahasa-store',
                'guard_name' => 'web',
                'permissions_label_id' => 25,
                'created_at' => '2023-10-25 08:31:10',
                'updated_at' => '2023-10-25 08:31:10',
                'deleted_at' => NULL,
            ),
            126 =>
            array(
                'id' => 152,
                'name' => 'kategori-create',
                'guard_name' => 'web',
                'permissions_label_id' => 26,
                'created_at' => '2023-10-25 08:34:49',
                'updated_at' => '2023-10-25 08:34:49',
                'deleted_at' => NULL,
            ),
            127 =>
            array(
                'id' => 153,
                'name' => 'kategori-show',
                'guard_name' => 'web',
                'permissions_label_id' => 26,
                'created_at' => '2023-10-25 08:34:49',
                'updated_at' => '2023-10-25 08:34:49',
                'deleted_at' => NULL,
            ),
            128 =>
            array(
                'id' => 154,
                'name' => 'kategori-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 26,
                'created_at' => '2023-10-25 08:34:49',
                'updated_at' => '2023-10-25 08:34:49',
                'deleted_at' => NULL,
            ),
            129 =>
            array(
                'id' => 155,
                'name' => 'kategori-update',
                'guard_name' => 'web',
                'permissions_label_id' => 26,
                'created_at' => '2023-10-25 08:34:49',
                'updated_at' => '2023-10-25 08:34:49',
                'deleted_at' => NULL,
            ),
            130 =>
            array(
                'id' => 156,
                'name' => 'kategori-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 26,
                'created_at' => '2023-10-25 08:34:49',
                'updated_at' => '2023-10-25 08:34:49',
                'deleted_at' => NULL,
            ),
            131 =>
            array(
                'id' => 157,
                'name' => 'kategori-store',
                'guard_name' => 'web',
                'permissions_label_id' => 26,
                'created_at' => '2023-10-25 08:34:49',
                'updated_at' => '2023-10-25 08:34:49',
                'deleted_at' => NULL,
            ),
            132 =>
            array(
                'id' => 158,
                'name' => 'paket-create',
                'guard_name' => 'web',
                'permissions_label_id' => 27,
                'created_at' => '2023-10-25 08:37:05',
                'updated_at' => '2023-10-25 08:37:05',
                'deleted_at' => NULL,
            ),
            133 =>
            array(
                'id' => 159,
                'name' => 'paket-show',
                'guard_name' => 'web',
                'permissions_label_id' => 27,
                'created_at' => '2023-10-25 08:37:05',
                'updated_at' => '2023-10-25 08:37:05',
                'deleted_at' => NULL,
            ),
            134 =>
            array(
                'id' => 160,
                'name' => 'paket-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 27,
                'created_at' => '2023-10-25 08:37:05',
                'updated_at' => '2023-10-25 08:37:05',
                'deleted_at' => NULL,
            ),
            135 =>
            array(
                'id' => 161,
                'name' => 'paket-update',
                'guard_name' => 'web',
                'permissions_label_id' => 27,
                'created_at' => '2023-10-25 08:37:05',
                'updated_at' => '2023-10-25 08:37:05',
                'deleted_at' => NULL,
            ),
            136 =>
            array(
                'id' => 162,
                'name' => 'paket-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 27,
                'created_at' => '2023-10-25 08:37:05',
                'updated_at' => '2023-10-25 08:37:05',
                'deleted_at' => NULL,
            ),
            137 =>
            array(
                'id' => 163,
                'name' => 'paket-store',
                'guard_name' => 'web',
                'permissions_label_id' => 27,
                'created_at' => '2023-10-25 08:37:05',
                'updated_at' => '2023-10-25 08:37:05',
                'deleted_at' => NULL,
            ),
            138 =>
            array(
                'id' => 164,
                'name' => 'member-create',
                'guard_name' => 'web',
                'permissions_label_id' => 28,
                'created_at' => '2023-10-25 08:37:31',
                'updated_at' => '2023-10-25 08:37:31',
                'deleted_at' => NULL,
            ),
            139 =>
            array(
                'id' => 165,
                'name' => 'member-show',
                'guard_name' => 'web',
                'permissions_label_id' => 28,
                'created_at' => '2023-10-25 08:37:31',
                'updated_at' => '2023-10-25 08:37:31',
                'deleted_at' => NULL,
            ),
            140 =>
            array(
                'id' => 166,
                'name' => 'member-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 28,
                'created_at' => '2023-10-25 08:37:31',
                'updated_at' => '2023-10-25 08:37:31',
                'deleted_at' => NULL,
            ),
            141 =>
            array(
                'id' => 167,
                'name' => 'member-update',
                'guard_name' => 'web',
                'permissions_label_id' => 28,
                'created_at' => '2023-10-25 08:37:32',
                'updated_at' => '2023-10-25 08:37:32',
                'deleted_at' => NULL,
            ),
            142 =>
            array(
                'id' => 168,
                'name' => 'member-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 28,
                'created_at' => '2023-10-25 08:37:32',
                'updated_at' => '2023-10-25 08:37:32',
                'deleted_at' => NULL,
            ),
            143 =>
            array(
                'id' => 169,
                'name' => 'member-store',
                'guard_name' => 'web',
                'permissions_label_id' => 28,
                'created_at' => '2023-10-25 08:37:32',
                'updated_at' => '2023-10-25 08:37:32',
                'deleted_at' => NULL,
            ),
            144 =>
            array(
                'id' => 170,
                'name' => 'memberpaket-create',
                'guard_name' => 'web',
                'permissions_label_id' => 29,
                'created_at' => '2023-10-25 08:38:13',
                'updated_at' => '2023-10-25 08:38:13',
                'deleted_at' => NULL,
            ),
            145 =>
            array(
                'id' => 171,
                'name' => 'memberpaket-show',
                'guard_name' => 'web',
                'permissions_label_id' => 29,
                'created_at' => '2023-10-25 08:38:13',
                'updated_at' => '2023-10-25 08:38:13',
                'deleted_at' => NULL,
            ),
            146 =>
            array(
                'id' => 172,
                'name' => 'memberpaket-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 29,
                'created_at' => '2023-10-25 08:38:13',
                'updated_at' => '2023-10-25 08:38:13',
                'deleted_at' => NULL,
            ),
            147 =>
            array(
                'id' => 173,
                'name' => 'memberpaket-update',
                'guard_name' => 'web',
                'permissions_label_id' => 29,
                'created_at' => '2023-10-25 08:38:13',
                'updated_at' => '2023-10-25 08:38:13',
                'deleted_at' => NULL,
            ),
            148 =>
            array(
                'id' => 174,
                'name' => 'memberpaket-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 29,
                'created_at' => '2023-10-25 08:38:13',
                'updated_at' => '2023-10-25 08:38:13',
                'deleted_at' => NULL,
            ),
            149 =>
            array(
                'id' => 175,
                'name' => 'memberpaket-store',
                'guard_name' => 'web',
                'permissions_label_id' => 29,
                'created_at' => '2023-10-25 08:38:13',
                'updated_at' => '2023-10-25 08:38:13',
                'deleted_at' => NULL,
            ),
            150 =>
            array(
                'id' => 176,
                'name' => 'memberaktifitas-create',
                'guard_name' => 'web',
                'permissions_label_id' => 30,
                'created_at' => '2023-10-25 08:40:53',
                'updated_at' => '2023-10-25 08:40:53',
                'deleted_at' => NULL,
            ),
            151 =>
            array(
                'id' => 177,
                'name' => 'memberaktifitas-show',
                'guard_name' => 'web',
                'permissions_label_id' => 30,
                'created_at' => '2023-10-25 08:40:53',
                'updated_at' => '2023-10-25 08:40:53',
                'deleted_at' => NULL,
            ),
            152 =>
            array(
                'id' => 178,
                'name' => 'memberaktifitas-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 30,
                'created_at' => '2023-10-25 08:40:53',
                'updated_at' => '2023-10-25 08:40:53',
                'deleted_at' => NULL,
            ),
            153 =>
            array(
                'id' => 179,
                'name' => 'memberaktifitas-update',
                'guard_name' => 'web',
                'permissions_label_id' => 30,
                'created_at' => '2023-10-25 08:40:53',
                'updated_at' => '2023-10-25 08:40:53',
                'deleted_at' => NULL,
            ),
            154 =>
            array(
                'id' => 180,
                'name' => 'memberaktifitas-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 30,
                'created_at' => '2023-10-25 08:40:53',
                'updated_at' => '2023-10-25 08:40:53',
                'deleted_at' => NULL,
            ),
            155 =>
            array(
                'id' => 181,
                'name' => 'memberaktifitas-store',
                'guard_name' => 'web',
                'permissions_label_id' => 30,
                'created_at' => '2023-10-25 08:40:53',
                'updated_at' => '2023-10-25 08:40:53',
                'deleted_at' => NULL,
            ),
            156 =>
            array(
                'id' => 182,
                'name' => 'job-create',
                'guard_name' => 'web',
                'permissions_label_id' => 31,
                'created_at' => '2023-11-06 14:50:47',
                'updated_at' => '2023-11-06 14:50:47',
                'deleted_at' => NULL,
            ),
            157 =>
            array(
                'id' => 183,
                'name' => 'job-show',
                'guard_name' => 'web',
                'permissions_label_id' => 31,
                'created_at' => '2023-11-06 14:50:47',
                'updated_at' => '2023-11-06 14:50:47',
                'deleted_at' => NULL,
            ),
            158 =>
            array(
                'id' => 184,
                'name' => 'job-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 31,
                'created_at' => '2023-11-06 14:50:48',
                'updated_at' => '2023-11-06 14:50:48',
                'deleted_at' => NULL,
            ),
            159 =>
            array(
                'id' => 185,
                'name' => 'job-update',
                'guard_name' => 'web',
                'permissions_label_id' => 31,
                'created_at' => '2023-11-06 14:50:48',
                'updated_at' => '2023-11-06 14:50:48',
                'deleted_at' => NULL,
            ),
            160 =>
            array(
                'id' => 186,
                'name' => 'job-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 31,
                'created_at' => '2023-11-06 14:50:49',
                'updated_at' => '2023-11-06 14:50:49',
                'deleted_at' => NULL,
            ),
            161 =>
            array(
                'id' => 187,
                'name' => 'job-store',
                'guard_name' => 'web',
                'permissions_label_id' => 31,
                'created_at' => '2023-11-06 14:50:49',
                'updated_at' => '2023-11-06 14:50:49',
                'deleted_at' => NULL,
            ),
            162 =>
            array(
                'id' => 188,
                'name' => 'refrence-create',
                'guard_name' => 'web',
                'permissions_label_id' => 32,
                'created_at' => '2023-11-07 14:24:13',
                'updated_at' => '2023-11-07 14:24:13',
                'deleted_at' => NULL,
            ),
            163 =>
            array(
                'id' => 189,
                'name' => 'refrence-show',
                'guard_name' => 'web',
                'permissions_label_id' => 32,
                'created_at' => '2023-11-07 14:24:13',
                'updated_at' => '2023-11-07 14:24:13',
                'deleted_at' => NULL,
            ),
            164 =>
            array(
                'id' => 190,
                'name' => 'refrence-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 32,
                'created_at' => '2023-11-07 14:24:14',
                'updated_at' => '2023-11-07 14:24:14',
                'deleted_at' => NULL,
            ),
            165 =>
            array(
                'id' => 191,
                'name' => 'refrence-update',
                'guard_name' => 'web',
                'permissions_label_id' => 32,
                'created_at' => '2023-11-07 14:24:14',
                'updated_at' => '2023-11-07 14:24:14',
                'deleted_at' => NULL,
            ),
            166 =>
            array(
                'id' => 192,
                'name' => 'refrence-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 32,
                'created_at' => '2023-11-07 14:24:14',
                'updated_at' => '2023-11-07 14:24:14',
                'deleted_at' => NULL,
            ),
            167 =>
            array(
                'id' => 193,
                'name' => 'refrence-store',
                'guard_name' => 'web',
                'permissions_label_id' => 32,
                'created_at' => '2023-11-07 14:24:15',
                'updated_at' => '2023-11-07 14:24:15',
                'deleted_at' => NULL,
            ),
            168 =>
            array(
                'id' => 194,
                'name' => 'setting-create',
                'guard_name' => 'web',
                'permissions_label_id' => 33,
                'created_at' => '2023-11-08 10:15:10',
                'updated_at' => '2023-11-08 10:15:10',
                'deleted_at' => NULL,
            ),
            169 =>
            array(
                'id' => 195,
                'name' => 'setting-show',
                'guard_name' => 'web',
                'permissions_label_id' => 33,
                'created_at' => '2023-11-08 10:15:10',
                'updated_at' => '2023-11-08 10:15:10',
                'deleted_at' => NULL,
            ),
            170 =>
            array(
                'id' => 196,
                'name' => 'setting-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 33,
                'created_at' => '2023-11-08 10:15:10',
                'updated_at' => '2023-11-08 10:15:10',
                'deleted_at' => NULL,
            ),
            171 =>
            array(
                'id' => 197,
                'name' => 'setting-update',
                'guard_name' => 'web',
                'permissions_label_id' => 33,
                'created_at' => '2023-11-08 10:15:11',
                'updated_at' => '2023-11-08 10:15:11',
                'deleted_at' => NULL,
            ),
            172 =>
            array(
                'id' => 198,
                'name' => 'setting-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 33,
                'created_at' => '2023-11-08 10:15:11',
                'updated_at' => '2023-11-08 10:15:11',
                'deleted_at' => NULL,
            ),
            173 =>
            array(
                'id' => 199,
                'name' => 'setting-store',
                'guard_name' => 'web',
                'permissions_label_id' => 33,
                'created_at' => '2023-11-08 10:15:12',
                'updated_at' => '2023-11-08 10:15:12',
                'deleted_at' => NULL,
            ),
            174 =>
            array(
                'id' => 200,
                'name' => 'bidangnilai-create',
                'guard_name' => 'web',
                'permissions_label_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            175 =>
            array(
                'id' => 201,
                'name' => 'bidangnilai-show',
                'guard_name' => 'web',
                'permissions_label_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            176 =>
            array(
                'id' => 202,
                'name' => 'bidangnilai-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            177 =>
            array(
                'id' => 203,
                'name' => 'bidangnilai-update',
                'guard_name' => 'web',
                'permissions_label_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            178 =>
            array(
                'id' => 204,
                'name' => 'bidangnilai-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            179 =>
            array(
                'id' => 205,
                'name' => 'bidangnilai-store',
                'guard_name' => 'web',
                'permissions_label_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            180 =>
            array(
                'id' => 206,
                'name' => 'nomenklatur-create',
                'guard_name' => 'web',
                'permissions_label_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            181 =>
            array(
                'id' => 207,
                'name' => 'nomenklatur-show',
                'guard_name' => 'web',
                'permissions_label_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            182 =>
            array(
                'id' => 208,
                'name' => 'nomenklatur-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            183 =>
            array(
                'id' => 209,
                'name' => 'nomenklatur-update',
                'guard_name' => 'web',
                'permissions_label_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            184 =>
            array(
                'id' => 210,
                'name' => 'nomenklatur-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            185 =>
            array(
                'id' => 211,
                'name' => 'nomenklatur-store',
                'guard_name' => 'web',
                'permissions_label_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            186 =>
            array(
                'id' => 212,
                'name' => 'nomenklaturtahun-show',
                'guard_name' => 'web',
                'permissions_label_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            187 =>
            array(
                'id' => 213,
                'name' => 'nomenklaturtahun-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            188 =>
            array(
                'id' => 214,
                'name' => 'nomenklaturtahun-update',
                'guard_name' => 'web',
                'permissions_label_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            189 =>
            array(
                'id' => 215,
                'name' => 'nomenklaturtahun-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            190 =>
            array(
                'id' => 216,
                'name' => 'nomenklaturtahun-store',
                'guard_name' => 'web',
                'permissions_label_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            191 =>
            array(
                'id' => 217,
                'name' => 'nomenklaturtahun-create',
                'guard_name' => 'web',
                'permissions_label_id' => 36,
                'created_at' => '2023-11-20 13:45:14',
                'updated_at' => '2023-11-20 13:45:14',
                'deleted_at' => NULL,
            ),
            192 =>
            array(
                'id' => 218,
                'name' => 'note-create',
                'guard_name' => 'web',
                'permissions_label_id' => 37,
                'created_at' => '2023-11-20 14:38:42',
                'updated_at' => '2023-11-20 14:38:42',
                'deleted_at' => NULL,
            ),
            193 =>
            array(
                'id' => 219,
                'name' => 'note-show',
                'guard_name' => 'web',
                'permissions_label_id' => 37,
                'created_at' => '2023-11-20 14:38:43',
                'updated_at' => '2023-11-20 14:38:43',
                'deleted_at' => NULL,
            ),
            194 =>
            array(
                'id' => 220,
                'name' => 'note-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 37,
                'created_at' => '2023-11-20 14:38:43',
                'updated_at' => '2023-11-20 14:38:43',
                'deleted_at' => NULL,
            ),
            195 =>
            array(
                'id' => 221,
                'name' => 'note-update',
                'guard_name' => 'web',
                'permissions_label_id' => 37,
                'created_at' => '2023-11-20 14:38:44',
                'updated_at' => '2023-11-20 14:38:44',
                'deleted_at' => NULL,
            ),
            196 =>
            array(
                'id' => 222,
                'name' => 'note-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 37,
                'created_at' => '2023-11-20 14:38:44',
                'updated_at' => '2023-11-20 14:38:44',
                'deleted_at' => NULL,
            ),
            197 =>
            array(
                'id' => 223,
                'name' => 'note-store',
                'guard_name' => 'web',
                'permissions_label_id' => 37,
                'created_at' => '2023-11-20 14:38:45',
                'updated_at' => '2023-11-20 14:38:45',
                'deleted_at' => NULL,
            ),
            198 =>
            array(
                'id' => 224,
                'name' => 'kodepos-create',
                'guard_name' => 'web',
                'permissions_label_id' => 38,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            199 =>
            array(
                'id' => 225,
                'name' => 'kodepos-show',
                'guard_name' => 'web',
                'permissions_label_id' => 38,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            200 =>
            array(
                'id' => 226,
                'name' => 'kodepos-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 38,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            201 =>
            array(
                'id' => 227,
                'name' => 'kodepos-update',
                'guard_name' => 'web',
                'permissions_label_id' => 38,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            202 =>
            array(
                'id' => 228,
                'name' => 'kodepos-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 38,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            203 =>
            array(
                'id' => 229,
                'name' => 'kodepos-store',
                'guard_name' => 'web',
                'permissions_label_id' => 38,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            204 =>
            array(
                'id' => 230,
                'name' => 'informasi-create',
                'guard_name' => 'web',
                'permissions_label_id' => 39,
                'created_at' => '2023-11-23 11:26:35',
                'updated_at' => '2023-11-23 11:26:35',
                'deleted_at' => NULL,
            ),
            205 =>
            array(
                'id' => 231,
                'name' => 'informasi-show',
                'guard_name' => 'web',
                'permissions_label_id' => 39,
                'created_at' => '2023-11-23 11:26:35',
                'updated_at' => '2023-11-23 11:26:35',
                'deleted_at' => NULL,
            ),
            206 =>
            array(
                'id' => 232,
                'name' => 'informasi-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 39,
                'created_at' => '2023-11-23 11:26:36',
                'updated_at' => '2023-11-23 11:26:36',
                'deleted_at' => NULL,
            ),
            207 =>
            array(
                'id' => 233,
                'name' => 'informasi-update',
                'guard_name' => 'web',
                'permissions_label_id' => 39,
                'created_at' => '2023-11-23 11:26:36',
                'updated_at' => '2023-11-23 11:26:36',
                'deleted_at' => NULL,
            ),
            208 =>
            array(
                'id' => 234,
                'name' => 'informasi-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 39,
                'created_at' => '2023-11-23 11:26:36',
                'updated_at' => '2023-11-23 11:26:36',
                'deleted_at' => NULL,
            ),
            209 =>
            array(
                'id' => 235,
                'name' => 'informasi-store',
                'guard_name' => 'web',
                'permissions_label_id' => 39,
                'created_at' => '2023-11-23 11:26:36',
                'updated_at' => '2023-11-23 11:26:36',
                'deleted_at' => NULL,
            ),
            210 =>
            array(
                'id' => 236,
                'name' => 'sumberdata-show',
                'guard_name' => 'web',
                'permissions_label_id' => 40,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            211 =>
            array(
                'id' => 237,
                'name' => 'sumberdata-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 40,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            212 =>
            array(
                'id' => 238,
                'name' => 'sumberdata-create',
                'guard_name' => 'web',
                'permissions_label_id' => 40,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            213 =>
            array(
                'id' => 239,
                'name' => 'sumberdata-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 40,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            214 =>
            array(
                'id' => 240,
                'name' => 'sumberdata-update',
                'guard_name' => 'web',
                'permissions_label_id' => 40,
                'created_at' => '2023-12-03 16:22:01',
                'updated_at' => '2023-12-03 16:22:01',
                'deleted_at' => NULL,
            ),
            215 =>
            array(
                'id' => 241,
                'name' => 'sumberdata-store',
                'guard_name' => 'web',
                'permissions_label_id' => 40,
                'created_at' => '2023-12-03 16:22:01',
                'updated_at' => '2023-12-03 16:22:01',
                'deleted_at' => NULL,
            ),
            216 =>
            array(
                'id' => 242,
                'name' => 'bidangnilai-publish',
                'guard_name' => 'web',
                'permissions_label_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            217 =>
            array(
                'id' => 243,
                'name' => 'version-create',
                'guard_name' => 'web',
                'permissions_label_id' => 43,
                'created_at' => '2024-03-01 16:28:14',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            218 =>
            array(
                'id' => 244,
                'name' => 'version-show',
                'guard_name' => 'web',
                'permissions_label_id' => 43,
                'created_at' => '2024-03-01 16:28:14',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            219 =>
            array(
                'id' => 245,
                'name' => 'version-store',
                'guard_name' => 'web',
                'permissions_label_id' => 43,
                'created_at' => '2024-03-01 16:28:54',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            220 =>
            array(
                'id' => 246,
                'name' => 'version-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 43,
                'created_at' => '2024-03-01 16:28:54',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            221 =>
            array(
                'id' => 247,
                'name' => 'version-update',
                'guard_name' => 'web',
                'permissions_label_id' => 43,
                'created_at' => '2024-03-01 16:29:37',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            222 =>
            array(
                'id' => 248,
                'name' => 'version-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 43,
                'created_at' => '2024-03-01 16:29:37',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            223 =>
            array(
                'id' => 249,
                'name' => 'version-publish',
                'guard_name' => 'web',
                'permissions_label_id' => 43,
                'created_at' => '2024-03-01 16:30:25',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            224 =>
            array(
                'id' => 250,
                'name' => 'logUpload-create',
                'guard_name' => 'web',
                'permissions_label_id' => 44,
                'created_at' => '2024-05-03 14:51:04',
                'updated_at' => '2024-05-03 14:51:04',
                'deleted_at' => NULL,
            ),
            225 =>
            array(
                'id' => 251,
                'name' => 'logUpload-show',
                'guard_name' => 'web',
                'permissions_label_id' => 44,
                'created_at' => '2024-05-03 14:51:04',
                'updated_at' => '2024-05-03 14:51:04',
                'deleted_at' => NULL,
            ),
            226 =>
            array(
                'id' => 252,
                'name' => 'logUpload-edit',
                'guard_name' => 'web',
                'permissions_label_id' => 44,
                'created_at' => '2024-05-03 14:51:05',
                'updated_at' => '2024-05-03 14:51:05',
                'deleted_at' => NULL,
            ),
            227 =>
            array(
                'id' => 253,
                'name' => 'logUpload-update',
                'guard_name' => 'web',
                'permissions_label_id' => 44,
                'created_at' => '2024-05-03 14:51:05',
                'updated_at' => '2024-05-03 14:51:05',
                'deleted_at' => NULL,
            ),
            228 =>
            array(
                'id' => 254,
                'name' => 'logUpload-delete',
                'guard_name' => 'web',
                'permissions_label_id' => 44,
                'created_at' => '2024-05-03 14:51:05',
                'updated_at' => '2024-05-03 14:51:05',
                'deleted_at' => NULL,
            ),
            229 =>
            array(
                'id' => 255,
                'name' => 'logUpload-store',
                'guard_name' => 'web',
                'permissions_label_id' => 44,
                'created_at' => '2024-05-03 14:51:05',
                'updated_at' => '2024-05-03 14:51:05',
                'deleted_at' => NULL,
            ),
        ));
    }
}
