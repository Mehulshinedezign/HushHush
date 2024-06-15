<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Size;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $size = [
            ['type' => 'type1', 'name' => 'XS/P'],
            ['type' => 'type1', 'name' => 'S'],
            ['type' => 'type1', 'name' => 'M'],
            ['type' => 'type1', 'name' => 'L'],
            ['type' => 'type1', 'name' => 'XL'],
            ['type' => 'type2', 'name' => '0'],
            ['type' => 'type2', 'name' => '2'],
            ['type' => 'type2', 'name' => '4'],
            ['type' => 'type2', 'name' => '6'],
            ['type' => 'type2', 'name' => '8'],
            ['type' => 'type2', 'name' => '10'],
            ['type' => 'type2', 'name' => '12'],
            ['type' => 'type3', 'name' => '23'],
            ['type' => 'type3', 'name' => '24'],
            ['type' => 'type3', 'name' => '25'],
            ['type' => 'type3', 'name' => '26'],
            ['type' => 'type3', 'name' => '27'],
            ['type' => 'type3', 'name' => '28'],
            ['type' => 'type3', 'name' => '29'],
            ['type' => 'type3', 'name' => '30'],
            ['type' => 'type3', 'name' => '31'],
            ['type' => 'type4', 'name' => 'OS'],
            ['type' => 'type5', 'name' => '5'],
            ['type' => 'type5', 'name' => '5.5'],
            ['type' => 'type5', 'name' => '6'],
            ['type' => 'type5', 'name' => '6.5'],
            ['type' => 'type5', 'name' => '7'],
            ['type' => 'type5', 'name' => '7.5'],
            ['type' => 'type5', 'name' => '8'],
            ['type' => 'type5', 'name' => '8.5'],
            ['type' => 'type5', 'name' => '9'],
            ['type' => 'type5', 'name' => '9.5'],
            ['type' => 'type5', 'name' => '10'],
            ['type' => 'type5', 'name' => '10.5'],
            ['type' => 'type5', 'name' => '11'],
            ['type' => 'type6', 'name' => '35'],
            ['type' => 'type6', 'name' => '35.5'],
            ['type' => 'type6', 'name' => '36'],
            ['type' => 'type6', 'name' => '36.5'],
            ['type' => 'type6', 'name' => '37'],
            ['type' => 'type6', 'name' => '37.5'],
            ['type' => 'type6', 'name' => '38'],
            ['type' => 'type6', 'name' => '38.5'],
            ['type' => 'type6', 'name' => '39'],
            ['type' => 'type6', 'name' => '40'],
            ['type' => 'type6', 'name' => '40.5'],
            ['type' => 'type6', 'name' => '41'],
        ];
                
        Size::insert($size);
    }
}
