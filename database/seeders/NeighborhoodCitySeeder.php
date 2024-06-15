<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NeighborhoodCity;


class NeighborhoodCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $neighborhoodcity = [
            ['name' => 'Manhattan', 'parent_id' => null],
            ['name' => 'Brooklyn', 'parent_id' => null],
            ['name' => 'New Jersey', 'parent_id' => null],
            ['name' => 'Battery Park', 'parent_id' => 1],
            ['name' => 'Bowery', 'parent_id' => 1],
            ['name' => 'Chelsea', 'parent_id' => 1],
            ['name' => 'Chinatown', 'parent_id' => 1],
            ['name' => 'East Village', 'parent_id' => 1],
            ['name' => 'Financial District', 'parent_id' => 1],
            ['name' => 'Flatiron', 'parent_id' => 1],
            ['name' => 'Gramercy', 'parent_id' => 1],
            ['name' => 'Greenwich Village', 'parent_id' => 1],
            ['name' => "Hell's Kitchen", 'parent_id' => 1],
            ['name' => "Kip's Bay", 'parent_id' => 1],
            ['name' => 'Lenox Hill', 'parent_id' => 1],
            ['name' => 'Lincoln Square', 'parent_id' => 1],
            ['name' => 'Lower East Side', 'parent_id' => 1],
            ['name' => 'Midtown', 'parent_id' => 1],
            ['name' => 'Midtown East', 'parent_id' => 1],
            ['name' => 'Murray Hill', 'parent_id' => 1],
            ['name' => 'Noho', 'parent_id' => 1],
            ['name' => 'Nolita', 'parent_id' => 1],
            ['name' => 'Soho', 'parent_id' => 1],
            ['name' => 'Times Square', 'parent_id' => 1],
            ['name' => 'Tribeca', 'parent_id' => 1],
            ['name' => 'Turtle Bay', 'parent_id' => 1],
            ['name' => 'Upper East Side', 'parent_id' => 1],
            ['name' => 'Upper West Side', 'parent_id' => 1],
            ['name' => 'West Village', 'parent_id' => 1],
            ['name' => 'Yorkville', 'parent_id' => 1],
            ['name' => 'Boerum Hill', 'parent_id' => 2],
            ['name' => 'Brooklyn Heights', 'parent_id' => 2],
            ['name' => 'Bushwick', 'parent_id' => 2],
            ['name' => 'Carroll Gardens', 'parent_id' => 2],
            ['name' => 'Cobble Hill', 'parent_id' => 2],
            ['name' => 'Downtown Brooklyn', 'parent_id' => 2],
            ['name' => 'Dumbo', 'parent_id' => 2],
            ['name' => 'East Williamsburg', 'parent_id' => 2],
            ['name' => 'Flatbush', 'parent_id' => 2],
            ['name' => 'Gowanus', 'parent_id' => 2],
            ['name' => 'Greenpoint', 'parent_id' => 2],
            ['name' => 'Kensington', 'parent_id' => 2],
            ['name' => 'Park Slope', 'parent_id' => 2],
            ['name' => 'Prospect Heights', 'parent_id' => 2],
            ['name' => 'Vinegar Hill', 'parent_id' => 2],
            ['name' => 'Williamsburg', 'parent_id' => 2],
            ['name' => 'Hoboken', 'parent_id' => 3],
            ['name' => 'Jersey City', 'parent_id' => 3],

        ];
        NeighborhoodCity::insert($neighborhoodcity);
    }
}
