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
        // Menambahkan kolom deleted_at ke setiap tabel
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('room_images', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('bonuses', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('boarding_houses', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus kolom deleted_at jika rollback
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('room_images', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('bonuses', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('boarding_houses', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
