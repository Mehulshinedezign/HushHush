<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $color = [
            ['name' => 'Red', 'color_code' => '#ff0000'],
            ['name' => 'Pink', 'color_code' => '#FFC0CB'],
            ['name' => 'Orange', 'color_code' => '#FFA500'],
            ['name' => 'Yellow', 'color_code' => '#FFFF00'],
            ['name' => 'Green', 'color_code' => '#008000'],
            ['name' => 'Blue', 'color_code' => '#0000FF'],
            ['name' => 'Purple', 'color_code' => '#800080'],
            ['name' => 'White', 'color_code' => '#FFFFFF'],
            ['name' => 'Off-White/Cream', 'color_code' => '#FFFDD0'],
            ['name' => 'Tan', 'color_code' => '#D2B48C'],
            ['name' => 'Gray', 'color_code' => '#808080'],
            ['name' => 'Black', 'color_code' => '#000000'],
            ['name' => 'Brown', 'color_code' => '#964B00'],
            ['name' => 'Multi', 'color_code' => '#ff00e1'],
            ['name' => 'Silver', 'color_code' => '#c0c0c0'],
            ['name' => 'Gold', 'color_code' => '#FFD700'],
        ];
        
        Color::insert($color);
    }
}
