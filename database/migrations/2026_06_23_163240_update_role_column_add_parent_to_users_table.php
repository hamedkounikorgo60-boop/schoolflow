<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE users_temp AS SELECT * FROM users
        ");

        Schema::drop('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['gestionnaire', 'enseignant', 'parent'])->default('enseignant');
            $table->string('telephone', 30)->nullable();
            $table->string('adresse', 255)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        DB::statement("
            INSERT INTO users (id, name, email, email_verified_at, password, role, telephone, adresse, remember_token, created_at, updated_at)
            SELECT id, name, email, email_verified_at, password, role, telephone, adresse, remember_token, created_at, updated_at
            FROM users_temp
        ");

        DB::statement("DROP TABLE users_temp");
    }

    public function down(): void
    {
        DB::statement("
            CREATE TABLE users_temp AS SELECT * FROM users
        ");

        Schema::drop('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['gestionnaire', 'enseignant'])->default('enseignant');
            $table->string('telephone', 30)->nullable();
            $table->string('adresse', 255)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        DB::statement("
            INSERT INTO users (id, name, email, email_verified_at, password, role, telephone, adresse, remember_token, created_at, updated_at)
            SELECT id, name, email, email_verified_at, password, role, telephone, adresse, remember_token, created_at, updated_at
            FROM users_temp
            WHERE role IN ('gestionnaire', 'enseignant')
        ");

        DB::statement("DROP TABLE users_temp");
    }
};
