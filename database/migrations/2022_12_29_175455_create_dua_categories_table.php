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
        Schema::create('dua_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('language_id')->constrained('languages');
            $table->integer('position')->nullable();
            $table->tinyInteger('status')->default(1)->comment("1=>active,0=>inactive");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dua_categories');
    }
};
