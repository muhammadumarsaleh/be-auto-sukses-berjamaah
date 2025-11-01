<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->enum('role', ['reseller', 'stokis', 'master_stokis', 'super_admin'])->default('reseller');
            $table->integer('points')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
        // Seed Super Admin default
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@asb.com',
            'password' => Hash::make('12345678'),
            'role' => 'super_admin',
            'avatar' => 'avatar-1.jpg',
            'email_verified_at' => now(),
        ]);
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
