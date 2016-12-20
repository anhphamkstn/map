<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(App\User::class, 100)->create();

        DB::table('users')->insert([
            'name' => 'System Administrator',
            'email' => 'sysadmin@gmail.com',
            'role_id'  => 0,
            'active' => true,
            'phone_1' => '1',
            'phone_2' => '2',
            'password' => bcrypt('skymapglobal123')
        ]);

        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'role_id'  => 1,
            'active' => true,
            'phone_1' => '1',
            'phone_2' => '2',
            'password' => bcrypt('skymapglobal123')
        ]);
    }
}
