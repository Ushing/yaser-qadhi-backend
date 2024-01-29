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
        Schema::create('duas', function (Blueprint $table) {
            $table->id();
            $table->integer('reference_id')->nullable();
            $table->string('title','150');
            $table->foreignId('dua_sub_category_id')->constrained('dua_sub_categories');
            $table->foreignId('language_id')->constrained('languages');
            $table->integer('position')->nullable();
            $table->string('audio')->nullable();
            $table->string('video')->nullable();
            $table->text('translation')->nullable();
            $table->text('transliteration')->nullable();
            $table->text('arabic_dua')->nullable();
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
        Schema::dropIfExists('duas');
    }
};
