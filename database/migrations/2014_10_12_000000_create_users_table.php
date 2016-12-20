<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->integer('avatar_id')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('role_id');
            $table ->boolean('active');
            $table ->string('phone_1');
            $table ->string('phone_2')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index(['name', 'email']);
        });

        Schema::table('users', function (Blueprint $table)
        {
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('tracking_locations');
        Schema::dropIfExists('tasks');
        Schema::drop('users');
    }
}
