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
                'name' => 'clothing_size',
                'category_image_name' => 'clothing_size',
                // 'category_image_url' => 'admin/category/Accessories.svg',
            ],
            [
                'name' => 'bra_size',
                'category_image_name' => 'bra_size',
                // 'category_image_url' => 'admin/category/Dresses.svg',
            ],
            [
                'name' => 'men_shoe',
                'category_image_name' => 'men_shoe',
                // 'category_image_url' => 'admin/category/Sets.svg',
            ],
            [
                'name' => 'women_shoe',
                'category_image_name' => 'women_shoe',
                // 'category_image_url' => 'admin/category/Tops.svg',
            ],
            // [
            //     'name' => 'Bottoms',
            //     'category_image_name' => 'Bottoms',
            //     'category_image_url' => 'admin/category/Bottoms.svg',
            // ],
            // [
            //     'name' => 'Shoes',
            //     'category_image_name' => 'Shoes',
            //     'category_image_url' => 'admin/category/Shoes.svg',
            // ],
            // [
            //     'name' => 'Bags',
            //     'category_image_name' => 'Bags',
            //     'category_image_url' => 'admin/category/Bags.svg',
            // ],
            // [
            //     'name' => 'Accessories',
            //     'category_image_name' => 'Accessories',
            //     'category_image_url' => 'admin/category/Accessories.svg',
            // ],
            // [
            //     'name' => 'Outerwear',
            //     'category_image_name' => 'Outerwear',
            //     'category_image_url' => 'admin/category/Outerwear.svg',
            // ],
            // [
            //     'name' => 'Resort',
            //     'category_image_name' => 'Resort',
            //     'category_image_url' => 'admin/category/Resort.svg',
            // ],
        ];

        Category::insert($category);
    }
}
