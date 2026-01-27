<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
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
        return [
            'name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => Role::Customer,
        ];
    }

    /**
     * Create an admin user.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes): array => [
            'role' => Role::Admin,
        ]);
    }

    /**
     * Create an admin user that should be notified.
     */
    public function admin_notifiable(): static
    {
        return $this->state(fn (array $attributes): array => [
            'role' => Role::Admin,
            'email' => config('custom.admin_email'),
        ]);
    }

    /**
     * Create a customer user.
     */
    public function customer(): static
    {
        return $this->state(fn (array $attributes): array => [
            'role' => Role::Customer,
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }
}
