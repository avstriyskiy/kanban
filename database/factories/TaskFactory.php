<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Ramsey\Uuid\Type\Integer;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     * @throws \Exception
     */
    public function definition()
    {
        return [
            'name' => 'Task made by factory',
            'description' => Str::random(20),
            'status' => random_int(1, 4),
            'deadline' => now(),
            'category_id' => 1,
        ];
    }
}
