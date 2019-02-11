<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name', 64);
            $table->string('address', 64);
            $table->string('city', 64);
            $table->string('state', 8);
            $table->integer('zipcode');
            $table->string('is_union', 8);
            $table->integer('member_number');
            $table->string('email', 32);
            $table->string('phone', 32);
            $table->integer('data_file_id');
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
        Schema::dropIfExists('members');
    }
}
