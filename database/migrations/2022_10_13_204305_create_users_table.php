<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Все регистрируемые пользователи изначально администраторы, менять можно только вручную в базе
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->default(1)->constrained('categories');
            $table->string('name');
            $table->string('password');
            $table->string('email');
            $table->string('remember_token')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
