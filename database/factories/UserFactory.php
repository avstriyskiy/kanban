<?php

namespace Database\Factories;

use App\Models\Department;
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
        $department_id = random_int(1, 7);

        $category_id = Department::find($department_id)->category_id;

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'qwerasdfg', // password
            'remember_token' => Str::random(10),
            'position' => $this->faker->jobTitle,
            'category_id' => $category_id,
            'department_id' => $department_id,
        ];
    }
}
