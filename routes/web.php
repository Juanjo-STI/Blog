<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



//Principal
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/all', [HomeController::class, 'all'])->name('home.all');


//Artículos:
Route::resource('articles', ArticleController::class)
                ->except('show')
                ->names('articles');
//Categorías:
Route::resource('categories', CategoryController::class)
                ->except('show')
                ->names('categories');

//Comentarios:
Route::resource('comments', CommentController::class)
        ->only('index', 'destroy')
        ->names('comments');

//Perfiles:
Route::resource('profiles', ProfileController::class)
        ->only('edit', 'update')
        ->names('profiles');
//Ver artículos
Route::get('article/{article}', [ArticleController::class, 'show'])->name('article.show');

//Ver artículos por categorías:
Route::get('category/{category}', [CategoryController::class, 'detail'])->name('categories.detail');

//Guardar comentarios:
Route::get('/comment', [CommentController::class, 'store'])->name('comments.store');

Auth::routes();

/**
 * Forma de crear las rutas de manera manual.
 * (Al utilizar resource, las crea automáticamente, hay que poner los except para que no hagas todas
 * en caso que corresponda. Fijarse en el show)
 */
//Artículos
// Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
// Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
// Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');

// Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
// Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
// Route::delete('/artciles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');