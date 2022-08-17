<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Mostrar las categorías en el Admin
        $categories = Category::orderBy('id', 'desc')
                        ->simplePaginate(8);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Retornamos la vista para crear categorias
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        //Traigo todos
        $category = $request->all();

        //Validar si hay un archivo.
        if($request->hasFile('image')){
            $category['image'] = $request->file('image')->store('categories');
        }

        //Guardado de la información
        Category::create($category);

        return redirect()->action([CategoryController::class, 'index'])
                        ->with('success-create', 'Categoría Creada con éxito');
    }

    public function edit(Category $category)
    {
        // Retornar la vista...
        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        //Si el usuario sube una imagen
        if($request->hasFile('image')){
            //Eliminar la imagen anterior
            File::delete(public_path('storage/'.$category->image));
            //Asignar la nueva imagen
            $category['image'] = $request->file('image')->store('categories');
        }

        //Actualizar datos
        $category->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'status' => $request->status,
            'is_featured' => $request->is_featured,
        ]);

        return redirect()->action([CategoryController::class, 'index'], compact('category'))
                         ->with('success-update', 'Categoría Modificada con éxito');        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //Eliminar imagen de la categoría para que no queden obsoletos
        if($category->image){
            File::delete(public_path('storage/'.$category->image));
        }
        $category->delete();

        return redirect()->action([CategoryController::class, 'index'], compact('category'))
                         ->with('success-delete', 'Categoría Eliminada con éxito');
    }

    /**
     * Filtrar Artículos por categorías, Hecho por nosotros.
     */
    public function detail(Category $category){
        $articles = Article::where([
            ['category_id', $category->id],
            ['status', '1'],
        ])
            ->orderBy('id', 'desc')
            ->simplePaginate(5);
        
        $navbar = Category::where([
            ['status', '1'],
            ['is_featured', 1],
        ])->paginate(3);

        return view('subscriber.categories.detail', compact('articles', 'navbar', 'category'));
    }
}
