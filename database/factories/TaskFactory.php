<?php

namespace Database\Factories;

use App\Models\Task;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Ramsey\Uuid\Type\Integer;

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

        $myDate = strtotime('20 November 2022 23:54:12');
        $now = new DateTime(now(new \DateTimeZone('Europe/Moscow')));
        $nowTimestamp = $now->getTimestamp();
        $deadlineTimestamp = mt_rand($nowTimestamp, $myDate);
        $deadline = new DateTime();
        $deadline = $deadline->setTimestamp($deadlineTimestamp);

        return [
            'name' => $array_of_names[$category_id],
            'description' => 'Описание',
            'status' => random_int(1, 4),
            'deadline' => $deadline->format('Y-m-d H-m-s'),
            'category_id' => $category_id,
        ];
    }
}
