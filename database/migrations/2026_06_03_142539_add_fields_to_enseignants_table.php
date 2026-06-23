<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('enseignants', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->after('id');
            $table->string('specialite')->nullable()->after('user_id');
            $table->string('telephone')->nullable()->after('specialite');
        });
    }
    public function down(): void {
        Schema::table('enseignants', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'specialite', 'telephone']);
        });
    }
};
