<?php

use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('books')->insert([
            "user_id" => 1,
            "title" => 'The Institute',
            "author" => 'Stephen King',
            "year" => 2019,
            "read" => true,
            "rating" => 4,
            "created_at" => "2019-12-10 03:56:45",
            "updated_at" => "2019-12-10 03:56:45",
        ]);

        DB::table('books')->insert([
            "user_id" => 1,
            "title" => 'Blue Mars',
            "author" => 'Kim Stanley Robinson',
            "year" => 1996,
            "read" => true,
            "rating" => 4,
            "created_at" => new DateTime(),
            "updated_at" => new DateTime(),
        ]);

        DB::table('books')->insert([
            "user_id" => 2,
            "title" => 'Omega',
            "author" => 'Jack McDevitt',
            "year" => 2002,
            "read" => true,
            "rating" => 5,
            "created_at" => new DateTime(),
            "updated_at" => new DateTime(),
        ]);


    }
}
