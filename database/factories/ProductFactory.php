<?php

namespace Database\Factories;

use App\Models\{User, Product, Category};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // get the retailers and individual 3->individual, 4->retailer
        $users = User::whereIn('role_id', [3,4])->get();
        $user = $users->random();
        $userId = $user->id;

        // retrieve all the product category id's
        $categories = Category::get();
        $category = $categories->random();
        $categoryId = $category->id;
        $percentage = 30;
        $quantity = 1;//$available = rand(1,9);
        $rent = $this->faker->randomFloat(0, 10, 49);
        // $price = $this->faker->randomFloat(0, 199, 999);
        $security = ($rent/$percentage)*100;

        return [
            'name' => $this->faker->unique()->word(),
            'description' => $this->faker->text(),
            // 'specification' => $this->faker->text(),
            'user_id' => $userId,
            'category_id' => $categoryId,
            'quantity' => $quantity,
            'rent' => $rent,
            // 'price' => $price,
            'security' => $security,
            // 'available' => $available,
        ];
    }
}
