@extends('layouts.app')


@section('botones')

<a class="btn btn-primary mr-2 text-white" href="{{ route('recetas.create') }}">Crear Receta</a>

@endsection

@section('content')

    <h2 class="text-center mb-5">Administra tus recetas</h2>

    <div class="col-md-10 mx-auto bg-white p-3">
        <table class="table">
            <thead class="bg-primary text-light">
                <tr>
                    <th scole="col">Titulo</th>
                    <th scole="col">Categoría</th>
                    <th scole="col">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($recetas as $receta)
                <tr>
                    <td>{{$receta->titulo}}</td>
                    <td>{{$receta->categoria->nombre}}</td>
                    <td>
                        <!-- Eliminando de la forma tradicional -->
                        <!-- <form action="{{route('recetas.destroy',['receta'=> $receta->id])}}" method="POST">
                            @csrf
                            @method('DELETE')
                        <input type="submit" class="btn btn-danger d-block w-100 mb-2" value="Eliminar &times;">
                        </form> -->
                        <!-- Eliminar con VueJs -->
                        <!-- Pasar el prop -->
                        <eliminar-receta receta-id={{$receta->id}}>
                            
                        </eliminar-receta>
                        <a href="{{route('recetas.edit', ['receta'=>$receta->id])}}" class="btn btn-dark d-block w-100 mb-2">Editar</a>
                        <a href="{{route('recetas.show', ['receta'=>$receta->id])}}" class="btn btn-success d-block w-100 mb-2">Ver</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection