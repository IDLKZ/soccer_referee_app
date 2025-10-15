<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();

        return [
            'role_id' => null,
            'image_url' => null,
            'last_name' => $lastName,
            'first_name' => $firstName,
            'patronomic' => fake()->optional()->firstName(),
            'phone' => fake()->unique()->e164PhoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'username' => Str::slug($lastName . '.' . $firstName . fake()->unique()->numberBetween(10, 999)),
            'sex' => fake()->randomElement([0, 1, 2]),
            'iin' => fake()->optional()->numerify('############'),
            'birth_date' => fake()->optional()->date(),
            'password_hash' => static::$password ??= Hash::make('password'),
            'is_active' => true,
            'is_verified' => true,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model should be inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
