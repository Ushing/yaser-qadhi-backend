<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ReciteLanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('recite_languages')->insert([
            [
                'id' => 1,
                'title' => 'English',
                'slug' => 'english',
                'status' => 1,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'id' => 2,
                'title' => 'Bengali',
                'slug' => 'bengali',
                'status' => 1,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],

            [
                'id' => 3,
                'title' => 'Arabic',
                'slug' => 'arabic',
                'status' => 1,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
        ]);
    }
}
