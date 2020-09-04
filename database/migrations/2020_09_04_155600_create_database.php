<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->BigIncrements('id');
            $table->string('description', 100);
        });

        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->BigIncrements('id');
            $table->string('name', 150);
            $table->string('email', 100);
            $table->string('password', 150);
            $table->bigInteger('status_id')->unsigned();
            $table->boolean('is_admin')->default(0);
            $table->string('avatar', 100)->default('default.jpg');
            $table->string('token', 200)->nullable();

            $table->foreign('status_id')->references('id')->on('status');
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->BigIncrements('id');
            $table->string('name', 150);
            $table->bigInteger('status_id')->unsigned();

            $table->foreign('status_id')->references('id')->on('status');
        });

        Schema::create('items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->BigIncrements('id');
            $table->string('name', 150);
            $table->integer('minimum_stock');
            $table->bigInteger('status_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();

            $table->foreign('status_id')->references('id')->on('status');
            $table->foreign('category_id')->references('id')->on('categories');
        });

        Schema::create('stocks', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->BigIncrements('id');
            $table->bigInteger('item_id')->unsigned();
            $table->integer('actual_stock');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');

            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
        Schema::dropIfExists('items');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('users');
        Schema::dropIfExists('status');
    }
}
