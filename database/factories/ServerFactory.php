<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'host' => $this->faker->domainName,
            'ip' => $this->faker->ipv4,
            'description' => $this->faker->sentence,
            'image_path' => 'servers/fake-image.jpg',
            'sort_order' => 0,
        ];
    }
}