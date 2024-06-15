<?php

namespace Database\Factories;

use App\Models\{ProductRating, Product, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductRatingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductRating::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = User::where('role_id', 2)->get();
        $user = $users->random();
        $userId = $user->id;

        $products = Product::get();
        $product = $products->random();
        $productId = $product->id;
        
        return [
            'user_id' => $userId,
            'product_id' => $productId,
            'rating' => rand(2,10) / 2,
            'review' => $this->faker->text()
        ];
    }
}
