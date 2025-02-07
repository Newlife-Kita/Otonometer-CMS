<?php

use Illuminate\Database\Seeder;
use Database\Seeds\UsersTableSeeder;
use Database\Seeds\RoleHasPermissionsTableSeeder;
use Database\Seeds\RolesTableSeeder;
use Database\Seeds\PermissionsTableSeeder;
use Database\Seeds\PermissionsGroupTableSeeder;
use Database\Seeds\PermissionsLabelTableSeeder;
use Database\Seeds\ModelHasRolesTableSeeder;
use Database\Seeds\ModelHasPermissionsTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(UsersTableSeeder::class);
        $this->call(RoleHasPermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(PermissionsGroupTableSeeder::class);
        $this->call(PermissionsLabelTableSeeder::class);
        $this->call(ModelHasRolesTableSeeder::class);
        $this->call(ModelHasPermissionsTableSeeder::class);
    }
}
