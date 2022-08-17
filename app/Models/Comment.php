<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    //Campos que no quiero que se asignen masivamente.
    protected $guarded = ['id', 'created_at', 'updated_at'];

    //Relación de uno a muchos inversa (comment-user)
    public function user(){
        return $this->belongsTo(User::class);
    }

    //Relación de uno a muchos inversa (comment-article)
    public function article(){
        return $this->belongsTo(Article::class);
    }
}
