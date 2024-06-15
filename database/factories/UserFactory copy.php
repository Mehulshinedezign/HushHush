<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\{User, Role, Country, State, City};

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $roles = Role::where('id', '>', 1)->get();
        $role = $roles->random();
        $roleId = $role->id;

        // $countries = Country::get();
        // $country = $countries->random();
        // $countryId = $country->id;

        // $states = State::where('country_id', $countryId)->get();
        // $state = $states->random();
        // $stateId = $state->id;

        // $cities = City::where('state_id', $stateId)->get();
        // $city = $cities->random();
        // $cityId = $city->id;

        $countryId = 231;
        $stateId = 3924;
        $cityId = 42798;

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'address1' => $this->faker->address(),
            'address2' => $this->faker->streetAddress(),
            'phone_number' => $this->faker->phoneNumber(),
            'postcode' => $this->faker->postcode(),
            'role_id' => $roleId,
            'country_id' => $countryId,
            'state_id' => $stateId,
            'city_id' => $cityId,
            'status' => 'Active'
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
