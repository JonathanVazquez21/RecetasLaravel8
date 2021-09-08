<a class="btn btn-outline-primary mr-2 text-uppercase font-weight-bold" href="{{ route('recetas.create') }}">Crear Receta</a>
<a class="btn btn-outline-success mr-2 text-uppercase font-weight-bold" href="{{ route('perfiles.edit', ['perfil' => Auth::user()->id])}}">
    
Editar Perfil</a>