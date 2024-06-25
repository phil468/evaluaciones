<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

//Agregamos spatie
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'personal_id',
        // 'personal_id',
        'area_id',
        'registrador',
        'estado'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    //agregar propiedad computarizada que traiga el nombre del rol del usuario
    // agregarlo como campo
    protected $appends = ['role', 'role_id'];
    // public function tipo_materiales()
    // {
    //     return $this->belongsToMany(TipoMaterial::class,'asignaciones','user_id','tipo_material_id');
    // }

    public function roles()
    {
        return $this->belongsToMany(Role::class,'model_has_roles','model_id','role_id');
    }
    
    public function personal()
    {
        return $this->hasOne('App\Models\Personal', 'id', 'personal_id');
    }    

        /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    //agregar campo computarizado que traiga el nombre del rol del usuario
    // agregarlo como campo
    public function getRoleAttribute()
    {
        return $this->roles()->first()->name;
    }

    public function getRoleIdAttribute()
    {
        return $this->roles()->first()->id;
    }
    
    public function canAccessFilament(): bool
    {
        return str_ends_with($this->email, '@vanguardfresh.pe') && $this->hasVerifiedEmail();
    }    
    
    public function adminlte_profile_url()
    {
        return 'users/'.auth()->user()->id.'';
    }
    
}