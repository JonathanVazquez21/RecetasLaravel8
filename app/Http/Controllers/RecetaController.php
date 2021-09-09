<?php

namespace App\Http\Controllers;

use App\Models\CategoriaReceta;
use App\Models\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class RecetaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Auth::user()->recetas->dd();
        
        //    $recetas = auth()->user()->recetas;
        //$meGusta = auth()->user()->meGusta;

        $usuario = auth()->user();
        //Recetas con Paginacion
        $recetas = Receta::where('user_id', $usuario->id)->paginate(4);

       return view ('recetas.index')->with('recetas', $recetas)
                                    ->with('usuario',$usuario);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //DB::table('categoria_recetas')->get()->pluck('nombre', 'id')->dd();

        //Obtener categorias sin modelo
        //$categorias = DB::table('categoria_recetas')->get()->pluck('nombre', 'id');

        //Obtener categorias con modelo
        $categorias = CategoriaReceta::all(['id','nombre']);

        return view('recetas.create')->with('categorias',$categorias);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        //Validaciones
        $data = request()->validate([
            'titulo' => 'required|min:6',
            'preparacion' => 'required', 
            'ingredientes' => 'required',
            'imagen' => 'required|image',  
            'categoria' => 'required',
        ]);

        //Ruta de las imagenes
        $ruta_imagen = $request['imagen']->store('upload-recetas','public');
        
        //Cambiar el tamaño de la imagen (Resize)
        $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(1000,550);
        $img->save();

        //Almacenar en la bd sin modelo
        // DB::table('recetas')->insert([
        //     'titulo' => $data['titulo'],
        //     'preparacion' => $data['preparacion'],
        //     'ingredientes' => $data['ingredientes'],
        //     'imagen' => $ruta_imagen,
        //     'user_id' => Auth::user()->id,
        //     'categoria_id' => $data['categoria']

        // ]);

        //Almacenar en la bd con modelo
        auth()->user()->recetas()->create([
            'titulo' => $data['titulo'],
            'preparacion' => $data['preparacion'],
            'ingredientes' => $data['ingredientes'],
            'imagen' => $ruta_imagen,
            'categoria_id' => $data['categoria']
       ]);

        //Redireccionando
        return redirect()->action([RecetaController::class, 'index']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function show(Receta $receta)
    {
        /**Metodos alternos  */
        //$receta = Receta::find($receta);
        //$receta = Receta::findOrFail($receta);

        //Obtener si el usuario actual le gusta la receta y esta autenticado
        $like = ( auth()->user() ) ? auth()->user()->meGusta->contains($receta->id) :false;

        //Pasando la cantidad de likes a la vista

        $likes = $receta->likes->count();


        
        return view('recetas.show', compact('receta','like','likes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function edit(Receta $receta)
    {
          //Revisar el policy
          $this->authorize('view',$receta);

        //Obtener categorias con modelo
        $categorias = CategoriaReceta::all(['id','nombre']);
        //Obtener la copia de la receta
        return view('recetas.edit', compact('categorias','receta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receta $receta)
    {

        //Revisar el policy
        $this->authorize('update',$receta);

        // return $receta;

        //Validaciones
        $data = request()->validate([
            'titulo' => 'required|min:6',
            'preparacion' => 'required', 
            'ingredientes' => 'required', 
            'categoria' => 'required',
        ]);

        //Asignacion de valore

        $receta->titulo = $data['titulo'];
        $receta->preparacion = $data['preparacion'];
        $receta->ingredientes = $data['ingredientes'];
        $receta->categoria_id = $data['categoria'];

        //Si el usuario sube una nueva imagen
        if (request('imagen')) {
            //Ruta de las imagenes
            $ruta_imagen = $request['imagen']->store('upload-recetas','public');
            
            //Cambiar el tamaño de la imagen (Resize)
            $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(1000,550);
            $img->save();
            //Asignacion al objeto
            $receta->imagen = $ruta_imagen;
        }


        $receta->save();
         //Redireccionando
         return redirect()->action([RecetaController::class, 'index']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receta $receta)
    {
        
        // Ejecucion del Policy
        $this->authorize('delete', $receta );
        //Eliminar la receta
        $receta->delete();
        
        //Redireccionando
        return redirect()->action([RecetaController::class, 'index']);
    }
}
