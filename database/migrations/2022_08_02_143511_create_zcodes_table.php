<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zcodes', function (Blueprint $table) {
            $table->integer('zip_code')->unique();
            $table->string('locality')->nullable();
            $table->string('state')->nullable();
            $table->string('state_code')->nullable();
            $table->integer('zip_code_key')->nullable();
            $table->string('municipality')->nullable();
            $table->string('municipality_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zcodes');
    }
};
