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
        Schema::create('lecture_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('lecture_category_id')->constrained('lecture_categories');
            $table->foreignId('language_id')->constrained('languages');
            $table->integer('position')->nullable();
            $table->tinyInteger('status')->default(1)->comment("1=>active,0=>Inactive");
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
        Schema::dropIfExists('lecture_sub_categories');
    }
};
