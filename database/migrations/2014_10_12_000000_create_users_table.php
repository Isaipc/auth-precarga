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
        Schema::table('inscaptur', function (Blueprint $table) {
            $table->string('nombre', 100)->change();
            $table->string('login', 9)->change();
            $table->string('password', 50)->change();
        });

        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('email')->unique();
        //     $table->timestamp('email_verified_at')->nullable();
        //     $table->string('password');
        //     $table->rememberToken();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inscaptur', function (Blueprint $table) {
            $table->string('nombre', 100)->change();
            $table->string('login', 9)->change();
            $table->string('password', 50)->change();
        });
        // Schema::dropIfExists('users');
    }
}
