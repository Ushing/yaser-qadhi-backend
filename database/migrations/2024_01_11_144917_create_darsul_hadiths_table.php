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
        Schema::create('darsul_hadiths', function (Blueprint $table) {
            $table->id();
            $table->string('title','150');
            $table->integer('reference_id')->nullable();
            $table->string('video')->nullable();
            $table->string('audio')->nullable();
            $table->tinyInteger('status')->default(1)->comment("1=>active,0=>Inactive");
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
        Schema::dropIfExists('darsul_hadiths');
    }
};
