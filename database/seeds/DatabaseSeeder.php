<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         //$this->call(UsersTableSeeders::class);
         $this->call(CmsUsersTableSeeders::class);
         $this->call(LinksTableSeeder::class);
         $this->call(NoticiasTableSeeder::class);
         $this->call(QuemsomosTableSeeder::class);
         $this->call(SettingsTableSeeder::class);
         $this->call(WebdoorsTableSeeder::class);
         $this->call(MenuTableSeeder::class);
         $this->call(IndicesTableSeeder::class);
         $this->call(IdiomaTableSeeder::class);
    }
}
