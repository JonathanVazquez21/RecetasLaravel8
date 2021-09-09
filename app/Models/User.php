<?php

namespace App\Models;

use App\Models\Perfil;
use App\Models\Receta;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'url',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Evento que se ejecuta cuando un usuario es creado
    protected static function boot(){
        parent::boot();

        //Asignar perfil una vez se haya creado un usuario nuevo
        static::created(function($user) {
            $user->perfil()->create();
        });
    }

    /** Relacion 1:n Usuario a Recetas */
    public function recetas()
    {
        return $this->hasMany(Receta::class);
    }

    //Relacion de 1:1 de usuarios y perfil
    public function perfil(){

        return $this->hasOne(Perfil::class);
    }

    //Recetas que el usuario le ha dado me gusta
    public function meGusta(){
        return $this->belongsToMany(Receta::class,
        'likes_receta',
        'receta_id',
        'user_id');
    }

}
