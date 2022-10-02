<?php

namespace asciito\BlogPackage\Tests;

use Orchestra\Testbench\Factories\UserFactory as OrchestraUserFactory;

class UserFactory extends OrchestraUserFactory
{
    protected $model = User::class;
    
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => \Illuminate\Support\Str::random(10),
        ];
    }
}