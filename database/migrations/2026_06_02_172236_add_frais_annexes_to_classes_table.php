<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {

            $table->decimal('frais_inscription',10,2)->default(0);

            $table->decimal('frais_cantine',10,2)->default(0);

            $table->decimal('frais_transport',10,2)->default(0);

            $table->decimal('frais_fournitures',10,2)->default(0);

            $table->decimal('autres_frais',10,2)->default(0);

        });
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {

            $table->dropColumn([
                'frais_inscription',
                'frais_cantine',
                'frais_transport',
                'frais_fournitures',
                'autres_frais'
            ]);

        });
    }
};
