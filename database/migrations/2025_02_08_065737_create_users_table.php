<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('phone_no')->nullable();
            $table->string('password');
            $table->string('photo')->nullable();
            $table->boolean('status')->default(1);
            $table->unsignedBigInteger('role_id');
            $table->tinyInteger('gender')->nullable();
            $table->string('user_lang', '2')->default('kh');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

