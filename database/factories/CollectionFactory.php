<?php

namespace Database\Factories;

use App\Models\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

class CollectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Collection::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'amount_goal' => $this->faker->randomNumber(7),
            'created_at' => $this->faker->dateTimeBetween('-6 months', '-1 month'),
        ];
    }

    public function pledges(){
        return $this
            ->afterCreating(function (Collection $collection) {

                PledgeFactory::new()->count($this->faker->numberBetween(5,25))->create([
                    'collection_id' => $collection->id,
                ]);

        });
    }
}
