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
		\DB::table('users')->insert([
			[
				'username' => 'admin',
				'name' => 'Admin',
				'email' => 'admin@gmail.com',
				'password' => '$2y$10$uICAZyiBZzyDEc4SUSVfJeWsFL6X/4oQvZE1lH0pjY/66pqWn4rHe',
				'level_id' => 2,
				'is_active' => 1,
				'created_at' => '2016-08-17 09:00:00',
				'updated_at' => '2016-08-17 09:00:00'
			], [
				'username' => 'pandudud',
				'name' => 'Pandu Yudhantara',
				'email' => 'pandudud@gmail.com',
				'password' => '$2y$10$vTw47ajbTfgfdUTbODRal.S1/CNHOl7l76eEkbeMiLp/SqqzUdpsG',
				'level_id' => 1,
				'is_active' => 1,
				'created_at' => '2016-08-17 09:00:00',
				'updated_at' => '2016-08-17 09:00:00'
			], [
				'username' => 'user',
				'name' => 'User',
				'email' => 'user@gmail.com',
				'password' => '$2y$10$ALvIRVXSLPMYsw4OUlCseOALP5OEsa/p8mNLe4HndD4vaDYYoS5Au',
				'level_id' => 3,
				'is_active' => 1,
				'created_at' => '2016-08-17 09:00:00',
				'updated_at' => '2016-08-17 09:00:00'
			]
		]);
	}
}
