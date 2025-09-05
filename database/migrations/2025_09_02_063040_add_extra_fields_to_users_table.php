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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'mobile')) {
                $table->string('mobile', 15)->nullable();
            }
            if (!Schema::hasColumn('users', 'dob')) {
                $table->date('dob')->nullable();
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable();
            }
            if (!Schema::hasColumn('users', 'profile_image')) {
                $table->string('profile_image')->nullable();
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active');
            }
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['mobile', 'dob', 'gender', 'profile_image', 'status']);
        });
    }
};
