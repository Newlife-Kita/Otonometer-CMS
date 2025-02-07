<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;
use illuminate\Support\Facades\DB;

class ModelHasPermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('model_has_permissions')->delete();
    }
}
