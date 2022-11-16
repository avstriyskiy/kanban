<?php

namespace Database\Factories;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{

    protected $model = Task::class;
    /**
     * Define the model's default state.
     *
     * @return array
     * @throws \Exception
     */
    public function definition()
    {
        $array_of_names = [
            1 => 'Задача для Администратора',
            2 => 'Задача для Менеджера',
            3 => 'Задача для Разработчика'
        ];

        $category_id = random_int(1, 3);

        $today = Carbon::now('Europe/Moscow');
        $weekAfter = Carbon::now('Europe/Moscow')->addWeek();
        $diff = mt_rand($today->timestamp, $weekAfter->timestamp);
        $deadline = Carbon::createFromTimestamp($diff);

        return [
            'name' => $array_of_names[$category_id],
            'description' => 'Описание',
            'status' => random_int(1, 4),
            'deadline' => $deadline,
            'category_id' => $category_id,
        ];
    }
}
