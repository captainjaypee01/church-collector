<?php

namespace Database\Factories;

use App\Models\Pledge;
use Illuminate\Database\Eloquent\Factories\Factory;

class PledgeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pledge::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(2,200),
            'amount' => $this->faker->randomNumber(5),
            'created_at' => $this->faker->dateTimeBetween('-5 months', '-1 month'),
        ];
    }
}
