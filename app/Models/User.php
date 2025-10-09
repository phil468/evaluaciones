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
    
    public function hasPendingEvaluations()
    {
        // Si no tiene un personal asociado, no puede tener evaluaciones
        if (!$this->personal_id || !$this->personal) {
            return false;
        }
        
        // Verificar evaluaciones pendientes como evaluador
        $pendientesComoEvaluador = $this->personal->evaluadorHasEvaluados()
            ->whereHas('evaluacion', function($query) {
                // Usar condiciones que representen "activa" basadas en columnas reales
                // Por ejemplo, si "activa" significa que está en fechas válidas:
                $today = now()->format('Y-m-d H:i:s');
                // Ajusta las condiciones según tu modelo de Evaluacion
                // Aquí asumimos que 'estado' es una columna que indica si la evaluación está activa
                // y que 'fecha_inicio' y 'fecha_fin' son las fechas de inicio y
                // fin de la evaluación.
                // Asegúrate de que estas columnas existan en tu modelo Evaluacion.                
                $query->where('status', 1) // Asumiendo que estado=1 significa activa
                    ->where('fecha_inicio', '<=', $today)
                    ->where('fecha_fin', '>=', $today);
                // Ajusta estas condiciones según cómo se calcula realmente "activa" en tu modelo
            })
            ->get()
            ->filter(function($evaluacion) {
                return $evaluacion->estado_pendiente;
            })
            ->count() > 0;
    
        

    // Pendientes como encargado de planes de acción (relación con plan_de_mejora)
    $pendientesComoEncargadoPlanes = $this->personal->planesComoEncargado()
        ->habilitado()
        ->whereHas('plan_de_mejora', function($query) {
            $today = now();
            $query->where('status', 1)
                ->where(function($q) use ($today) {
                    $q->where(function($sub) use ($today) {
                        $sub->where('fecha_inicio_primera_fase_matricula', '<=', $today)
                            ->where('fecha_fin_primera_fase_matricula', '>=', $today);
                    })
                    ->orWhere(function($sub) use ($today) {
                        $sub->where('fecha_inicio_segunda_fase', '<=', $today)
                            ->where('fecha_fin_segunda_fase', '>=', $today);
                    });
                });
        })
        ->get()
        ->contains(fn($p) => $p->estado_pendiente);

        // dd(
        //     $this->personal->planesComoEncargado()
        //         ->habilitado()
        //         ->whereHas('plan_de_mejora', function($query) {
        //         $today = now();
        //         $query->where('status', 1)
        //             ->where(function($q) use ($today) {
        //                 $q->where(function($sub) use ($today) {
        //                     $sub->where('fecha_inicio_primera_fase_matricula', '<=', $today)
        //                         ->where('fecha_fin_primera_fase_matricula', '>=', $today);
        //                 })
        //                 ->orWhere(function($sub) use ($today) {
        //                     $sub->where('fecha_inicio_segunda_fase', '<=', $today)
        //                         ->where('fecha_fin_segunda_fase', '>=', $today);
        //                 });
        //             });
        //         })
        //         ->get() ,

        //         $this->personal->planesComoEncargado()
        //         ->habilitado()
        //         ->whereHas('plan_de_mejora', function($query) {
        //         $today = now();
        //         $query->where('status', 1)
        //             ->where(function($q) use ($today) {
        //                 $q->where(function($sub) use ($today) {
        //                     $sub->where('fecha_inicio_primera_fase_matricula', '<=', $today)
        //                         ->where('fecha_fin_primera_fase_matricula', '>=', $today);
        //                 })
        //                 ->orWhere(function($sub) use ($today) {
        //                     $sub->where('fecha_inicio_segunda_fase', '<=', $today)
        //                         ->where('fecha_fin_segunda_fase', '>=', $today);
        //                 });
        //             });
        //         })
        //         ->first()->cantidad_requerida ,

        //         $this->personal->planesComoEncargado()
        //         ->habilitado()
        //         ->whereHas('plan_de_mejora', function($query) {
        //         $today = now();
        //         $query->where('status', 1)
        //             ->where(function($q) use ($today) {
        //                 $q->where(function($sub) use ($today) {
        //                     $sub->where('fecha_inicio_primera_fase_matricula', '<=', $today)
        //                         ->where('fecha_fin_primera_fase_matricula', '>=', $today);
        //                 })
        //                 ->orWhere(function($sub) use ($today) {
        //                     $sub->where('fecha_inicio_segunda_fase', '<=', $today)
        //                         ->where('fecha_fin_segunda_fase', '>=', $today);
        //                 });
        //             });
        //         })
                
        //         ->first()->estado_pendiente ,

        //         $this->personal->planesComoEncargado()
        //         ->habilitado()
        //         ->whereHas('plan_de_mejora', function($query) {
        //         $today = now();
        //         $query->where('status', 1)
        //             ->where(function($q) use ($today) {
        //                 $q->where(function($sub) use ($today) {
        //                     $sub->where('fecha_inicio_primera_fase_matricula', '<=', $today)
        //                         ->where('fecha_fin_primera_fase_matricula', '>=', $today);
        //                 })
        //                 ->orWhere(function($sub) use ($today) {
        //                     $sub->where('fecha_inicio_segunda_fase', '<=', $today)
        //                         ->where('fecha_fin_segunda_fase', '>=', $today);
        //                 });
        //             });
        //         })
                
        //         ->first()->planes_de_accion_empleado ,

        //         )
        //     ;


        // Verificar evaluaciones pendientes como evaluado
        // $pendientesComoEvaluado = $this->personal->evaluadoHasEvaluadors()
        //     ->whereHas('evaluacion', function($query) {
        //         $query->where('activa', true);
        //     })
        //     ->get()
        //     ->filter(function($evaluacion) {
        //         return $evaluacion->estado_pendiente;
        //     })
        //     ->count() > 0;
        
        // return $pendientesComoEvaluador || $pendientesComoEvaluado;
        return $pendientesComoEvaluador || $pendientesComoEncargadoPlanes;
    }

}