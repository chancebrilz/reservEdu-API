<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('nces_id')->unique();
            $table->string('name');
            $table->string('district');
            $table->string('address');
            $table->float('lat', 10, 6);
            $table->float('lng', 10, 6);
            $table->string('phone');
            $table->string('email');
            $table->boolean('activated');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('schools');
    }
}
