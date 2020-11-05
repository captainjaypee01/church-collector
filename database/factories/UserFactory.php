<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'birthdate' => $this->faker->date('Y-m-d', 'now'),
            'contact_number' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'location_id' => $this->faker->numberBetween(1,10),
        ];
    }

    public function members(){
        return $this
            ->afterCreating(function (User $user) {
            $user->roles()->attach(2);
            MemberFactory::new()->create([
                'user_id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'contact_number' => $user->contact_number,
                'birthdate' => $user->birthdate,
                'address' => $user->address,
                'leader_id' => $this->faker->numberBetween(1,50),
            ]);

        });
    }

    public function leaders(){
        return $this
            ->afterCreating(function (User $user) {
                $user->roles()->attach(4);
            LeaderFactory::new()->create([
                'user_id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'contact_number' => $user->contact_number,
                'birthdate' => $user->birthdate,
                'address' => $user->address,
            ]);

        });
    }
}
