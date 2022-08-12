<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Llamado al Seeder:
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ArticleSeeder::class);

        //Factory:
        Comment::factory(20)->create();
    }
}
