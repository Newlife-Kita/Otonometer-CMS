<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;
use illuminate\Support\Facades\DB;

class PermissionsGroupTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('permissions_group')->insertOrIgnore(array(
            0 =>
            array(
                'id' => 1,
                'name' => 'User Management',
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 =>
            array(
                'id' => 2,
                'name' => 'Registration',
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            2 =>
            array(
                'id' => 3,
                'name' => 'Master Data',
                'created_at' => '2023-10-16 12:06:19',
                'updated_at' => '2023-10-16 12:06:19',
                'deleted_at' => NULL,
            ),
            3 =>
            array(
                'id' => 4,
                'name' => 'Nilai Rill & Perkapita',
                'created_at' => '2023-10-18 09:39:37',
                'updated_at' => '2023-10-18 09:39:52',
                'deleted_at' => NULL,
            ),
            4 =>
            array(
                'id' => 5,
                'name' => 'Pemda & DPRD',
                'created_at' => '2023-10-18 09:42:47',
                'updated_at' => '2023-10-18 09:42:47',
                'deleted_at' => NULL,
            ),
            5 =>
            array(
                'id' => 6,
                'name' => 'Akun Member',
                'created_at' => '2023-10-25 08:52:16',
                'updated_at' => '2023-10-25 08:52:16',
                'deleted_at' => NULL,
            ),
            6 =>
            array(
                'id' => 7,
                'name' => 'App Management',
                'created_at' => '2024-03-01 16:32:36',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            7 =>
            array(
                'id' => 8,
                'name' => 'Log Cms',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
        ));
    }
}
