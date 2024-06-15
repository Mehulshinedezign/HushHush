<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = (int) $this->command->ask('Number of products to be created?', 50);
        Product::factory()->count($count)->create();       
        $this->command->info($count . ' products have been created');
    }
}
