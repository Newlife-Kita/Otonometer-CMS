<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;
use illuminate\Support\Facades\DB;

class ModelHasRolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('model_has_roles')->insertOrIgnore(array(
            0 =>
            array(
                'role_id' => 1,
                'model_type' => 'App\\User',
                'model_id' => 1,
            ),
            1 =>
            array(
                'role_id' => 3,
                'model_type' => 'App\\User',
                'model_id' => 2,
            ),
            2 =>
            array(
                'role_id' => 1,
                'model_type' => 'App\\User',
                'model_id' => 3,
            ),
            3 =>
            array(
                'role_id' => 2,
                'model_type' => 'App\\User',
                'model_id' => 4,
            ),
            4 =>
            array(
                'role_id' => 2,
                'model_type' => 'App\\User',
                'model_id' => 5,
            ),
            5 =>
            array(
                'role_id' => 2,
                'model_type' => 'App\\User',
                'model_id' => 6,
            ),
            6 =>
            array(
                'role_id' => 2,
                'model_type' => 'App\\User',
                'model_id' => 9,
            ),
            7 =>
            array(
                'role_id' => 5,
                'model_type' => 'App\\User',
                'model_id' => 13,
            ),
            8 =>
            array(
                'role_id' => 1,
                'model_type' => 'App\\User',
                'model_id' => 19,
            ),
        ));
    }
}
