<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i<=30; $i++){
            Article::create([
                'title' => 'Título '.$i,
                'slug' => 'titulo'.$i,
                'introduction' => 'Una introducción...',
                'image' => 'articles/'.$i.'.png',
                'body' => 'Un cuerpo...',
                'status' => $i%2,
                'user_id' => ($i%3)+1,
                'category_id' => ($i%6)+1,
            ]);
        }
    }
}
