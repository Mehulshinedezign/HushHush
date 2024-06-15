<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = [
            [
                'name' => 'New',
                'category_image_name' => 'New',
                'category_image_url' => 'admin/category/Accessories.svg',
            ],
            [
                'name' => 'Dresses',
                'category_image_name' => 'Dresses',
                'category_image_url' => 'admin/category/Dresses.svg',
            ],
            [
                'name' => 'Sets',
                'category_image_name' => 'Sets',
                'category_image_url' => 'admin/category/Sets.svg',
            ],
            [
                'name' => 'Tops',
                'category_image_name' => 'Tops',
                'category_image_url' => 'admin/category/Tops.svg',
            ],
            [
                'name' => 'Bottoms',
                'category_image_name' => 'Bottoms',
                'category_image_url' => 'admin/category/Bottoms.svg',
            ],
            [
                'name' => 'Shoes',
                'category_image_name' => 'Shoes',
                'category_image_url' => 'admin/category/Shoes.svg',
            ],
            [
                'name' => 'Bags',
                'category_image_name' => 'Bags',
                'category_image_url' => 'admin/category/Bags.svg',
            ],
            [
                'name' => 'Accessories',
                'category_image_name' => 'Accessories',
                'category_image_url' => 'admin/category/Accessories.svg',
            ],
            [
                'name' => 'Outerwear',
                'category_image_name' => 'Outerwear',
                'category_image_url' => 'admin/category/Outerwear.svg',
            ],
            // [
            //     'name' => 'Resort',
            //     'category_image_name' => 'Resort',
            //     'category_image_url' => 'admin/category/Resort.svg',
            // ],
        ];

        Category::insert($category);
    }
}
