<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductRating;

class ProductRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = (int) $this->command->ask('Number of products ratings to be created?', 50);
        ProductRating::factory()->count($count)->create();       
        $this->command->info($count . ' products ratings have been created');
    }
}
