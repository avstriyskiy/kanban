<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (!Category::find(1)){
            for ($i = 1; $i <= 3; $i++){
                if ($i == 1){
                    $name = 'Администратор';
                    $description = 'Пользователь с полным доступом';
                } elseif ($i == 2){
                    $name = 'Менеджер';
                    $description = 'Сотрудники - менеджеры';
                } else {
                    $name = 'Разработчик';
                    $description = 'Сотрудники - разработчики';
                }
                Category::create([
                    'name' => $name,
                    'description' => $description,
                ]);
            }
        }

        Task::factory(15)->create();

        User::factory(20)->create();
    }
}
