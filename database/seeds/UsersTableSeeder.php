<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->insertOrIgnore(array(
            0 =>
            array(
                'id' => 1,
                'name' => 'Administrator',
                'email' => 'admin@redtech.co.id',
                'email_verified_at' => '2023-10-16 12:03:45',
                'image' => 'https://via.placeholder.com/500',
                'id_wilayah' => NULL,
                'password' => '$2y$10$DWDd.gl57sQemRGT3M6xxeZ4adz/T6W6vikedJn0ypFggCqC10rjW',
                'remember_token' => 'xNqIehk2yXtiYMDVaWwBrDkJodXgXXSJjEEJJuMbYo7y81Y4VZq721UsM427',
                'created_at' => '2023-10-16 12:03:45',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 =>
            array(
                'id' => 2,
                'name' => 'Nurul Irhamni Budi',
                'email' => 'nurul@redtech.co.id',
                'email_verified_at' => NULL,
                'image' => NULL,
                'id_wilayah' => 240,
                'password' => '$2y$10$8aULqsm9npa9FXesXjZIXuBBEOCjZHRONsl0r3yRH/lbWwFmz56IK',
                'remember_token' => NULL,
                'created_at' => '2023-10-18 09:02:48',
                'updated_at' => '2023-10-18 09:17:32',
                'deleted_at' => '2023-11-14 00:07:00',
            ),
            2 =>
            array(
                'id' => 3,
                'name' => 'Admin NR',
                'email' => 'admin@neracaruang.com',
                'email_verified_at' => NULL,
                'image' => NULL,
                'id_wilayah' => NULL,
                'password' => '$2y$10$Dc6qAtYIpV7OpBKLuazDT.i03IDnmXInt3L6PTYGGrl0dWjEWmfBm',
                'remember_token' => NULL,
                'created_at' => '2023-11-04 12:52:56',
                'updated_at' => '2024-02-02 14:23:36',
                'deleted_at' => NULL,
            ),
            3 =>
            array(
                'id' => 4,
                'name' => 'Akbar Verari',
                'email' => 'akbar.verary91@gmail.com',
                'email_verified_at' => NULL,
                'image' => NULL,
                'id_wilayah' => NULL,
                'password' => '$2y$10$pOpOi6JpeMI7jrid6PUixeYbzT8bHm7DkN5FZwVvFA7Q4jdw.zL3G',
                'remember_token' => NULL,
                'created_at' => '2024-01-26 19:33:50',
                'updated_at' => '2024-01-26 19:33:50',
                'deleted_at' => NULL,
            ),
            4 =>
            array(
                'id' => 5,
                'name' => 'Faiza',
                'email' => 'faizakhrns@gmail.com',
                'email_verified_at' => NULL,
                'image' => NULL,
                'id_wilayah' => NULL,
                'password' => '$2y$10$V7l.cSN/YhhCA5FkWtFjI.VndeSib/dPFvO28C8IQROnzLMG8lo02',
                'remember_token' => NULL,
                'created_at' => '2024-01-26 19:34:36',
                'updated_at' => '2024-01-26 19:34:36',
                'deleted_at' => NULL,
            ),
            5 =>
            array(
                'id' => 6,
                'name' => 'Afifa Rizk',
                'email' => 'afifarizkyputriii@gmail.com',
                'email_verified_at' => NULL,
                'image' => NULL,
                'id_wilayah' => NULL,
                'password' => '$2y$10$YITyeGrWiPeoRQ5CAsYvZujCcKFmnjjGyrAC39jk7ubBEsapgMuBC',
                'remember_token' => NULL,
                'created_at' => '2024-01-26 19:35:03',
                'updated_at' => '2024-04-06 21:03:05',
                'deleted_at' => NULL,
            ),
            6 =>
            array(
                'id' => 9,
                'name' => 'Nurul Irhamni',
                'email' => 'nurul.irhamni@redtech.co.id',
                'email_verified_at' => NULL,
                'image' => NULL,
                'id_wilayah' => NULL,
                'password' => '$2y$10$JfCq8V6HoO1GZSdggCGlLenFEhxseGBPVvHtzjY3DHDmn2mi0gSKS',
                'remember_token' => NULL,
                'created_at' => '2024-01-26 20:11:43',
                'updated_at' => '2024-02-02 13:54:54',
                'deleted_at' => NULL,
            ),
            7 =>
            array(
                'id' => 13,
                'name' => 'Shania',
                'email' => 'shaniaavi15@gmail.com',
                'email_verified_at' => NULL,
                'image' => NULL,
                'id_wilayah' => NULL,
                'password' => '$2y$10$nU1k7qTImebwgS8zLPfDX.OEhxocLj.ds3parEjkJQuugf3paVeCK',
                'remember_token' => NULL,
                'created_at' => '2024-01-26 20:17:15',
                'updated_at' => '2024-05-01 05:08:50',
                'deleted_at' => NULL,
            ),
            8 =>
            array(
                'id' => 14,
                'name' => 'Sandra',
                'email' => 'sandra@redbuzz.co.id',
                'email_verified_at' => NULL,
                'image' => NULL,
                'id_wilayah' => NULL,
                'password' => '$2y$10$h4sMqKrpsENQdShsmBHX6evkMgwOnqdJvhp1xpLAnIGzGeusG0.lO',
                'remember_token' => NULL,
                'created_at' => '2024-02-02 14:26:15',
                'updated_at' => '2024-02-02 14:26:15',
                'deleted_at' => NULL,
            ),
            9 =>
            array(
                'id' => 17,
                'name' => 'monica',
                'email' => 'monica@redbuzz.co.id',
                'email_verified_at' => NULL,
                'image' => NULL,
                'id_wilayah' => NULL,
                'password' => '$2y$10$aYACeixRsOW3eSF8T2V/ueCKEpssCRwY7marbLjnUx4rgiWC3qSMS',
                'remember_token' => NULL,
                'created_at' => '2024-02-02 14:39:59',
                'updated_at' => '2024-02-02 14:39:59',
                'deleted_at' => NULL,
            ),
            10 =>
            array(
                'id' => 19,
                'name' => 'Didi Yakub',
                'email' => 'didi.yakub@gmail.com',
                'email_verified_at' => NULL,
                'image' => NULL,
                'id_wilayah' => NULL,
                'password' => '$2y$10$FrnPDZ3JmukbCeR.v6QUx.gU7h1mHkfRMj21GTH8.YCqtuI4uR2kK',
                'remember_token' => NULL,
                'created_at' => '2024-02-04 22:46:47',
                'updated_at' => '2024-02-04 22:46:47',
                'deleted_at' => NULL,
            ),
        ));
    }
}
