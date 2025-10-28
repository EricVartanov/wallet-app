<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('from_user_id')
                ->nullable()
                ->after('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('to_user_id')
                ->nullable()
                ->after('from_user_id')
                ->constrained('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['from_user_id']);
            $table->dropColumn('from_user_id');

            $table->dropForeign(['to_user_id']);
            $table->dropColumn('to_user_id');
        });
    }
};
