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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('emp_id')->unique()->nullable();
            $table->string('username');
            $table->string('password');
            $table->rememberToken();
            $table->boolean('active')->default(true);
            $table->boolean('superadmin')->default(false);
            $table->boolean('admin')->default(false);
            $table->boolean('hrd')->default(false);
            $table->boolean('hd')->default(false);
            $table->boolean('producer')->default(false);
            $table->boolean('pm')->default(false);
            $table->boolean('coor')->default(false);
            $table->boolean('spv')->default(false);
            $table->boolean('officer')->default(false);
            $table->boolean('production')->default(false);
            $table->boolean('liveshoot')->default(false);
            $table->tinyInteger('blocked')->default(0);
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
