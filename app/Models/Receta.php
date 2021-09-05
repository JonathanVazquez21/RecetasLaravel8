<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    use HasFactory;
    
     // Campos que se agregaran
     protected $fillable = [
        'titulo','ingredientes', 'preparacion', 'imagen', 'categoria_id'
    ];

   // Obtener la categoria de la receta via FK
   public function categoria(){
      return $this->belongsTo(CategoriaReceta::class);
   }

   //Obtener la informacion del usuario Fk
   public function autor(){
      return $this->belongsTo(User::class,'user_id'); //Fk de esta tabla
   }

}
