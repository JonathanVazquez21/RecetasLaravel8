<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CategoriaReceta;

class InicioController extends Controller
{
    
    
    public function index(){
        //Obtener las recetas con mayor cantidad de votos
        // $votadas = Receta::has('likes','=', 2)->get();
        $votadas = Receta::withCount('likes')->orderBy('likes_count','desc')->take(3)->get();
        //Obtener las recetas mas nuevas
        //$nuevas = Receta::orderBy('created_at', 'DESC')->get();
        $nuevas = Receta::latest()->take(6)->get();
        //Obtener todas las categorias
        $categorias = CategoriaReceta::all();
        //Agrupar las recetas por categoria
        $recetas = [];
        foreach($categorias as $categoria){
            $recetas[ Str::slug($categoria->nombre)] [] = Receta::where('categoria_id', $categoria->id)->get();

        }


        //return $recetas;

        return view('inicio.index', compact('nuevas', 'recetas','votadas'));
    }
}
