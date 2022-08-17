<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Mostrar los artículos en el Admin.
        $user = Auth::user();//Info del usuario autenticado

        $articles = Article::where('user_id', $user->id)
                    ->orderBy('id', 'desc')
                    ->simplePaginate(10);

        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Obtenemos las categorías públicas.
        $categories = Category::select(['id', 'name'])
                        ->where('status', '1')
                        ->get();
        
        return view('admin.articles.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        //Con esto obtengo el id del usuario registrado en back-end
        $request->merge([
            'user_id' => Auth::user()->id,
        ]);

        //Guardo la solicitud en una variable
        $article = $request->all();

        //Validar si hay un archivo en el request
        if($request->hasFile('image')){
            $article['image'] = $request->file('image')->store('articles');
        }
        Article::create($article);
        return redirect()->action([ArticleController::class, 'index'])
                        ->with('success-create', 'Artículo Creado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        $comments = $article->comments()->simplePaginate(5);

        return view('subscriber.articles.show', compact('article', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //Similar al Create...
        //Obtenemos las categorías públicas.
        $categories = Category::select(['id', 'name'])
                        ->where('status', '1')
                        ->get();
        
        return view('admin.articles.edit', compact('categories', 'article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, Article $article)
    {
        //Si el usuario sube una nueva imagen. (Elimine la anterior y suba la nueva)
        if($request->hasFile('image')){
            //Eliminamos
            File::delete(public_path('storage/'.$article->image));

            //Asigna la nueva imagen
            $article['image'] = $request->file('image')->store('articles');
        }

        //Actualizar los datos:
        $article->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'introduction' => $request->introduction,
            'body' => $request->body,
            'user_id' => Auth::user()->id,
            'category_id' => $request->category_id,
            'status' => $request->status,
        ]);

        return redirect()->action([ArticleController::class, 'index'])
                         ->with('success-update', 'Artículo Modificado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //Eliminamos la imagen del artículo.
        if($article->image){
            File::delete(public_path('storage/'.$article->image));
        }

        //Eliminar artículo.
        $article->delete();
        return redirect()->action([ArticleController::class, 'index'], compact('article'))
                        ->with('success-delete', 'Artículo Eliminado con éxito');
    }
}
