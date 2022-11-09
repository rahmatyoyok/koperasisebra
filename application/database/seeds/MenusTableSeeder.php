<?php

use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('menus')->insert([
			[
				'id' => 1,
				'name' => 'Home',
				'link' => 'home',
				'parent_id' => 0,
				'icon' => 'icon-home',
				'no_urut' => 1,
				'is_heading' => 0
			], [
				'id' => 2,
				'name' => 'Pengaturan',
				'link' => 'pengaturan',
				'parent_id' => 0,
				'icon' => 'icon-settings',
				'no_urut' => 3,
				'is_heading' => 0
			], [
				'id' => 3,
				'name' => 'Menu',
				'link' => 'pengaturan/menu',
				'parent_id' => 2,
				'icon' => 'icon-grid',
				'no_urut' => 1,
				'is_heading' => 0
			], [
				'id' => 4,
				'name' => 'Level',
				'link' => 'pengaturan/level',
				'parent_id' => 2,
				'icon' => 'icon-layers',
				'no_urut' => 2,
				'is_heading' => 0
			], [
				'id' => 5,
				'name' => 'Hak Akses',
				'link' => 'pengaturan/hak-akses',
				'parent_id' => 2,
				'icon' => 'icon-note',
				'no_urut' => 3,
				'is_heading' => 0
			], [
				'id' => 6,
				'name' => 'User',
				'link' => 'pengaturan/user',
				'parent_id' => 2,
				'icon' => 'icon-users',
				'no_urut' => 4,
				'is_heading' => 0
			], [
				'id' => 7,
				'name' => 'Main Menu',
				'link' => '',
				'parent_id' => 0,
				'icon' => null,
				'no_urut' => 2,
				'is_heading' => 1
			]
		]);
    }
}
