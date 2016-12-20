<?php

use Illuminate\Database\Seeder;

class TeamSalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\TeamSale::class, 10)->create();
    }
}
