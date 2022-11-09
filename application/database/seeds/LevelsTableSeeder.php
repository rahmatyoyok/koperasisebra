<?php

use Illuminate\Database\Seeder;

class LevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('levels')->insert([
			[
				'id' => 1,
				'name' => 'superadmin',
				'description' => 'Superadmin',
				'created_at' => '2016-12-03 13:11:34',
				'updated_at' => '2016-12-03 13:11:37',
				'deleted_at' => null
			], [
				'id' => 2,
				'name' => 'admin',
				'description' => 'Admin',
				'created_at' => '2016-12-03 13:11:57',
				'updated_at' => '2016-12-03 13:12:00',
				'deleted_at' => null
			], [
				'id' => 3,
				'name' => 'user',
				'description' => 'User',
				'created_at' => '2016-12-03 13:12:07',
				'updated_at' => '2016-12-03 13:12:10',
				'deleted_at' => null
			]
		]);
    }
}
