<?php

namespace Database\Seeders;

use App\Models\Department;
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
                    $description = 'Пользователи с полным доступом';
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

        if (!Department::find(1)){
            for ($i = 1; $i <= 7; $i++){
                if ($i == 1){
                    $name = 'Отдел разработки';
                    $description = 'Разработчики';
                    $category_id = 3;
                    $parent_id = 0;
                } elseif ($i == 2){
                    $name = 'Административный департамент';
                    $description = 'Всякие разные руководители административные';
                    $category_id = 1;
                    $parent_id = 0;
                } elseif ($i == 3) {
                    $name = 'Отдел продаж';
                    $description = 'Сотрудники - продажники';
                    $category_id = 2;
                    $parent_id = 0;
                } elseif ($i == 4){
                    $name = 'Группа продаж №1';
                    $description = 'Первая группа отдела продаж';
                    $category_id = 2;
                    $parent_id = 3;
                } elseif ($i == 5) {
                    $name = 'Группа продаж №2';
                    $description = 'Вторая группа отдела продаж';
                    $category_id = 2;
                    $parent_id = 3;
                } elseif ($i == 6) {
                    $name = 'Разработчики на PHP';
                    $description = 'Сотрудники - разработчики на ПХП';
                    $category_id = 3;
                    $parent_id = 1;
                } elseif ($i == 7) {
                    $name = 'Разработчики на С++';
                    $description = 'Сотрудники - разработчики на плюсах';
                    $category_id = 3;
                    $parent_id = 1;
                }
                Department::create([
                    'name' => $name,
                    'description' => $description,
                    'parent_id' => $parent_id,
                    'category_id' => $category_id,
                ]);
            }
        }

        Task::factory(15)->create();

        User::factory(10)->create();
    }
}
