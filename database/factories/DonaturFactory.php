<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DonaturFactory extends Factory
{
    public function definition()
    {
        return [
            'nama' => $this->faker->name,
            'nomor_hp' => $this->faker->phoneNumber,
            'alamat' => $this->faker->address,
            'total_kontribusi' => 0,
            'active' => true,
        ];
    }
}
