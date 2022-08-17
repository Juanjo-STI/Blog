<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = DB::table('comments')
            ->join('articles', 'comments.article_id', '=', 'articles.id')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->select('comments.value', 'comments.description',
            'articles.title', 'users.full_name')
            ->where('articles.user_id', '=', Auth::user()->id)
            ->orderBy('articles.id', 'desc')
            ->get();
        return view('admin.comments.index', compact('comments'));
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request)
    {
        //Verificar si ya existe un comentario de nosotros.
        $result = Comment::where('user_id', Auth::user()->id)
                    ->where('article_id', $request->article_id)->exists();

        //Consulta para obtener el slug y el estado del artículo.
        $article = Article::select('status', 'slug')->find($request->article_id);

        //Si no existe y si el artado del artículo es público, comentar.
        if(!$result and $article->status == 1){
            Comment::create([
                'value' => $request->value,
                'description' => $request->description,
                'user_id' => Auth::user()->id,
                'article_id' => $request->article_id,
            ]);
            return redirect()->action([ArticleController::class, 'show'], [$article->slug]);
        }else{
            return redirect()->action([ArticleController::class, 'show'], [$article->slug])
                            ->with('success-error', 'Solo puedes comentar una vez');
        }
    }

   
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //Comment destroy..
        $comment->delete();
        return redirect()->action([CommentController::class, 'index'],compact('comment'))
                        ->with('success-delete', 'Comentario eliminado con éxito');
    }
}
