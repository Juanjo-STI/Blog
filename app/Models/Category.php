<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    //Campos que no quiero que se asignen masivamente.
    protected $guarded = ['id', 'created_at', 'updated_at'];

    //Relación de uno a muchos (article-category)
    public function articles(){
        return $this->hasMany(Article::class);
    }

    //Utilizar el slug en lugar del id
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
