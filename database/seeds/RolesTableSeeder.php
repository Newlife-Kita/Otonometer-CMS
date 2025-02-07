<?php
namespace Database\Seeds;

use Illuminate\Database\Seeder;
use illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('roles')->insertOrIgnore(array(
            0 =>
            array(
                'id' => 1,
                'name' => 'administrator',
                'guard_name' => 'web',
                'created_at' => '2023-10-16 12:03:46',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 =>
            array(
                'id' => 2,
                'name' => 'admin data',
                'guard_name' => 'web',
                'created_at' => '2023-10-17 16:59:44',
                'updated_at' => '2024-02-04 22:47:08',
                'deleted_at' => NULL,
            ),
            2 =>
            array(
                'id' => 3,
                'name' => 'admin wilayah',
                'guard_name' => 'web',
                'created_at' => '2023-10-17 17:00:05',
                'updated_at' => '2023-10-17 17:00:05',
                'deleted_at' => NULL,
            ),
            3 =>
            array(
                'id' => 5,
                'name' => 'data management',
                'guard_name' => 'web',
                'created_at' => '2024-04-30 20:54:36',
                'updated_at' => '2024-04-30 20:54:36',
                'deleted_at' => NULL,
            ),
        ));
    }
}
