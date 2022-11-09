<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		\DB::table('permissions')->insert([
			[
				'id' => 1,
				'menu_id' => 1,
				'level_id' => 1
			], [
				'id' => 2,
				'menu_id' => 2,
				'level_id' => 1
			], [
				'id' => 3,
				'menu_id' => 3,
				'level_id' => 1
			], [
				'id' => 4,
				'menu_id' => 4,
				'level_id' => 1
			], [
				'id' => 5,
				'menu_id' => 6,
				'level_id' => 1
			], [
				'id' => 6,
				'menu_id' => 5,
				'level_id' => 1
			], [
				'id' => 7,
				'menu_id' => 1,
				'level_id' => 2
			], [
				'id' => 8,
				'menu_id' => 2,
				'level_id' => 2
			], [
				'id' => 9,
				'menu_id' => 5,
				'level_id' => 2
			], [
				'id' => 10,
				'menu_id' => 6,
				'level_id' => 2
			], [
				'id' => 11,
				'menu_id' => 1,
				'level_id' => 3
			]
		]);
    }
}
