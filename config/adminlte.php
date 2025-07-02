<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'VANGUARD',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => true,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => 'Vanguard	Perú &trade;',
    'logo_img' => 'img/icon/Vanguard-Intl-icon-blue-1cWeb-med.png',
    'logo_img_class' => 'brand-image ml-0',
    'logo_img_xl' => 'img/icon/Logotipo Vertical - Grupo Vanguard Internacional.png',    
    'logo_img_xl_alt' => 'img/icon/Logotipo Vertical - Grupo Vanguard Internacional.png',
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Vanguard',

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => true,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-primary rounded-2xl',
    'classes_auth_header' => '',
    'classes_auth_body' => 'rounded-2xl',
    'classes_auth_footer' => 'bg-primary rounded-b-2xl',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary rounded-pill',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => ( env('APP_ENV')=='testing' || env('APP_ENV')=='local') ? 'bg-gradient-warning' : '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-light-lightblue elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-vanguard navbar-dark',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => '/home',
    'logout_url' => '/logout',
    'login_url' => '/login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // Navbar items:
        [
            'type'         => 'navbar-search',
            'text'         => 'search',
            'topnav_right' => false,
        ],
        [
            'type'         => 'fullscreen-widget',
            'topnav_right' => false,
        ],

        // Sidebar items:
        
        // ['header' => 'CAPACITACIONES', 'can' => ['ver-activo','ver-entrega','ver-devolucion'
        // // 'ver-reporte-detallado',
        // // 'ver-reporte-ingreso','ver-reporte salida'
        // ]],
        // [
        //     'text' => 'Activos',
        //     'url'  => 'activos',
        //     'icon' => 'fas fa-mobile-alt',
        //     'can'  => 'ver-activo',
        // ],
        // [
        //     'text' => 'Entregas',
        //     'url'  => 'entregas',
        //     'icon' => 'fas fa-sign-out-alt',
        //     'can'  => 'ver-entrega',
        // ],
        // [
        //     'text' => 'Devoluciones',
        //     'url'  => 'devoluciones',
        //     'icon' => 'fas fa-sign-in-alt',
        //     'can'  => 'ver-devolucion',
        // ],
        // ['header' => 'CAPACITACIONES', 'can'  => ['ver-capacitacion']],

        // [
        //     'text' => 'CAPACITACIONES',
        //     'url'  => 'capacitaciones',
        //     'icon' => 'fas fa-sign-in-alt',
        //     'can'  => 'ver-capacitacion',
        // ],
        
        // [
        //     'text' => 'Asistencias',
        //     'url'  => 'asistencias',
        //     'icon' => 'fas fa-sign-in-alt',
        //     'can'  => 'ver-capacitacion',
        // ],
        // pantalla de inicio 
        ['header' => 'Inicio', 'can'  => ['ver-dashboard']],

        [
            'text' => 'Inicio',
            // url: inicio e inicio/pendientes
            'url' => 'inicio',
            'icon' => 'fas fa-tachometer-alt',
            // 'can'  => 'ver-dashboard',
            'classes' => 'rounded-xl',
            'active' => ['inicio', 'inicio/*'],
        ],

        // [
        //     'text' => 'Capacitación',
        //     'url'  => 'capacitacion',
        //     'icon' => 'fas fa-sign-in-alt',
        //     'can'  => 'ver-capacitacion',
        //     'classes' => 'rounded-xl',
        // ],

        // [
        //     'text' => 'Asistencias',
        //     'url'  => 'asistencias',
        //     'icon' => 'fas fa-sign-in-alt',
        //     'can'  => 'ver-capacitacion',
        //     'classes' => 'rounded-xl',
        // ],
        ['header' => 'Evaluaciones', 'can'  => ['ver-evaluaciones-de-desempeno']],

        [
            'text' => 'Evaluación por Competencias',
            'url'  => 'evaluacion_de_competencias',
            'icon' => 'fas fa-pencil-alt',
            'can'  => 'ver-evaluaciones-de-desempeno',
            'classes' => 'rounded-xl',
            'active' => ['evaluacion_de_competencias', 'evaluacion_de_competencias/*'],
        ],

        [
            'text' => 'Evaluación por Competencias (ant.)',
            'url'  => 'evaluaciones-de-desempeno/1',
            'icon' => 'fas fa-pencil-alt',
            'can'  => 'ver-evaluaciones-de-desempeno',
            'classes' => 'rounded-xl',
        ],

        [
            'text' => 'Evaluación por Objetivos',
            'url'  => 'evaluaciones-de-desempeno/2',
            'icon' => 'fas fa-pencil-alt',
            'can'  => 'ver-evaluaciones-de-desempeno',
            'classes' => 'rounded-xl',
        ],

        [
            'text' => 'Feedback y Retroalimentación',
            'url'  => 'feedback',
            'icon' => 'fas fa-comments',
            'can'  => 'ver-evaluaciones-de-desempeno',
            'classes' => 'rounded-xl',
        ],

        [
            'text' => 'Planes de Acción/ Desarrollo',
            'url'  => 'planes-de-mejora/ingreso',
            'icon' => 'fas fa-pencil-alt',
            'can'  => 'ver-evaluaciones-de-desempeno',
            'classes' => 'rounded-xl',
        ],

        [
            'text' => 'Plan De Mejora',
            'url'  => 'plan-de-mejora',
            'icon' => 'fas fa-pencil-alt',
            'can'  => 'ver-evaluaciones-de-desempeno',
            'classes' => 'rounded-xl'
        ],

        [ 
            'text' => 'Recursos de apoyo',
            'url'  => 'recursos-de-apoyo',
            'icon' => 'fas fa-book',
            'can'  => 'ver-evaluaciones-de-desempeno',
            'classes' => 'rounded-xl',
        ],

        [
            'text' => 'Resultados de Equipo',
            'url'  => 'resultados-de-equipo',
            'icon' => 'fas fa-chart-bar',
            'can'  => 'ver-evaluaciones-de-desempeno',
            'classes' => 'rounded-xl',
            'active' => ['resultados-de-equipo', 'resultados-de-equipo/*']
        ],

        ['text' => 'Seguimiento de Evaluaciones',
        'can'  => ['ver-capacitacion'],
        'icon' => 'fas fa-tasks',
        'classes' => 'rounded-xl',
        'submenu' => [
                [
                    'text' => 'Respuestas Ev. por Competencias',
                    'url'  => 'respuestas',
                    'icon' => 'fas fa-list-ol',
                    'can'  => 'ver-seguimiento-respuestas',
                    'classes' => 'rounded-xl',
                ],

                [
                    'text' => 'Calibración de Ev. Por competencias',
                    'url'  => 'resumen-evaluacion',
                    'icon' => 'fas fa-list-ol',
                    'can'  => 'ver-calibracion',
                    'classes' => 'rounded-xl',
                ],
                    
                [
                    'text' => 'Respuestas Ev. por Resultados',
                    // 'url'  => 'objetivos',
                    'url'  => 'respuesta-evaluacion-resultados',
                    'icon' => 'fas fa-list-ol',
                    'can'  => 'ver-seguimiento-objetivos',
                    'classes' => 'rounded-xl',
                ],

                [
                    'text' => 'Planes de mejora',
                    'url'  => 'planes-de-accion',
                    'icon' => 'fas fa-cog fa-xs',
                    'can'  => 'ver-planes-de-accion',
                    'classes' => 'rounded-xl',
                ],
                
                [
                    'text' => 'Seguimiento Evaluadores',
                    'url'  => 'seguimiento_evaluadores',
                    'url'  => 'seguimiento-evaluadores',
                    'icon' => 'fas fa-list-ol',
                    'can'  => 'ver-seguimiento-evaluadores',
                    'classes' => 'rounded-xl',
                ],

                [
                    'text' => 'Seguimiento Evaluados',
                    'url'  => 'seguimiento_evaluados',
                    'icon' => 'fas fa-list-ol',
                    'can'  => 'ver-seguimiento-evaluados',
                    'classes' => 'rounded-xl',
                ],
                
                [
                    'text' => 'Dashboard',
                    'url'  => 'dashboard',
                    'icon' => 'fas fa-list-ol',
                    'can'  => 'ver-dashboard',
                    'classes' => 'rounded-xl',
                ],
                
            ]
        ],

        ['text' => 'Ajustes de usuarios', 'can'  => ['ver-user','ver-rol'],
        'icon' => 'fas fa-users-cog',
        'classes' => 'rounded-xl',
        'submenu' => [
                [
                    'text' => 'Personal',
                    'url'  => 'personal-table',
                    'icon' => 'fas fa-cog fa-xs',
                    'can'  => 'ver-personal',
                    'classes' => 'rounded-xl',
                ],
                [
                    'text' => 'Cargos',
                    'url'  => 'cargos',
                    'icon' => 'fas fa-briefcase fa-xs',
                    'can'  => 'ver-cargo',
                    'classes' => 'rounded-xl',
                ],
                [
                    'text' => 'Tipos de Relación Jerárquica',
                    'url'  => 'tipo_relacion_jerarquicas',
                    'icon' => 'fas fa-sitemap fa-xs',
                    'can'  => 'ver-tipo-relacion-jerarquica',
                    'classes' => 'rounded-xl',
                ],
                [
                    'text' => 'Usuarios',
                    'url'  => 'users',
                    'icon' => 'fas fa-fw fa-users',
                    'can'  => 'ver-user',
                    'classes' => 'rounded-xl',
                    'active' => ['regex:@^users/[0-9]+/edit$@','users/create']
                ],
                [
                    'text' => 'Roles',
                    'url'  => 'roles',
                    'icon' => 'fas fa-fw fa-lock',
                    'can'  => 'ver-rol',
                    'classes' => 'rounded-xl',
                    'active' => ['regex:@^roles/[0-9]+/edit$@','roles/create']
                ],
            ]
        ],

        // ['text' => 'Mantenimiento', 
        // 'icon' => 'fas fa-cogs',
        // 'can' => [
        //         'ver-estado',
        //         'ver-condicion',
        //         'ver-cargo',
        //         'ver-area',
        //         'ver-personal',
        //         'ver-empresa',
        //         'ver-sede',
        //         'ver-tema',//
        //         'ver-planilla',//
        //         'ver-tipo-de-capacitacion',//
        //         'ver-gerencia',
        //         'ver-modalidad'// //
        //         ],
        //     'classes' => 'rounded-xl',
        //     'submenu' => [
        //         [
        //             'text' => 'Personal',
        //             'url'  => 'personal',
        //             'icon' => 'fas fa-cog fa-xs',
        //             'can'  => 'ver-personal',
        //             'classes' => 'rounded-xl',
        //         ],
        //         [
        //             'text' => 'Empresas',
        //             'url'  => 'empresas',
        //             'icon' => 'fas fa-cog fa-xs',
        //             'can'  => 'ver-empresa',
        //             'classes' => 'rounded-xl',
        //         ],
        //         [
        //             'text' => 'Sedes',
        //             'url'  => 'sedes',
        //             'icon' => 'fas fa-cog fa-xs',
        //             'can'  => 'ver-sede',
        //             'classes' => 'rounded-xl',
        //         ],
        //         [
        //             'text' => 'Gerencias',
        //             'url'  => 'gerencias',
        //             'icon' => 'fas fa-cog fa-xs',
        //             'can'  => 'ver-gerencia',
        //             'classes' => 'rounded-xl',
        //         ],
        //         [
        //             'text' => 'Áreas',
        //             'url'  => 'areas',
        //             'icon' => 'fas fa-cog fa-xs',
        //             'can'  => 'ver-area',
        //             'classes' => 'rounded-xl',
        //         ],
        //         [
        //             'text' => 'Cargos',
        //             'url'  => 'cargos',
        //             'icon' => 'fas fa-cog fa-xs',
        //             'can'  => 'ver-cargo',
        //             'classes' => 'rounded-xl',
        //         ],
        //         [
        //             'text' => 'Planillas',
        //             'url'  => 'planillas',
        //             'icon' => 'fas fa-cog fa-xs',
        //             'can'  => 'ver-planilla',
        //             'classes' => 'rounded-xl',
        //         ],
        //         [
        //             'text' => 'Tipos de capacitaciones',
        //             'url'  => 'tipos_de_capacitaciones',
        //             'icon' => 'fas fa-cog fa-xs',
        //             'can'  => 'ver-tipo-de-capacitacion',
        //             'classes' => 'rounded-xl',
        //         ],
        //         [
        //             'text' => 'Estados de capacitaciones',
        //             'url'  => 'estados',
        //             'icon' => 'fas fa-cog fa-xs',
        //             'can'  => 'ver-estado',
        //             'classes' => 'rounded-xl',
        //         ],
        //         [
        //             'text' => 'Temas',
        //             'url'  => 'temas',
        //             'icon' => 'fas fa-cog fa-xs',
        //             'can'  => 'ver-tema',
        //             'classes' => 'rounded-xl',
        //         ],
        //         [
        //             'text' => 'Modalidades',
        //             'url'  => 'modalidades',
        //             'icon' => 'fas fa-cog fa-xs',
        //             'can'  => 'ver-modalidad',
        //             'classes' => 'rounded-xl',
        //         ],
        //     ]
        // ],

    
        [
            'text' => 'Ajustes de evaluaciones', 'can'  => ['ver-user','ver-rol'],
            'classes' => 'rounded-xl',
            'icon' => 'fas fa-cogs',
            'submenu' =>[
                [
                    'text' => 'Campañas',
                    'url'  => 'campanias',
                    'icon' => 'fas fa-flag',
                    'can'  => 'ver-campania',
                    'classes' => 'rounded-xl',
                ],

                [
                    'text' => 'Evaluaciones',
                    'url'  => 'evaluaciones',
                    'icon' => 'fas fa-cog fa-xs',
                    'can'  => 'ver-configuracion-evaluaciones',
                    'classes' => 'rounded-xl',
                ],
                
                [
                    'text' => 'Evaluadores',
                    'url'  => 'evaluadores',
                    'icon' => 'fas fa-cog fa-xs',
                    'can'  => 'ver-configuracion-evaluadores',
                    'classes' => 'rounded-xl',
                ],
                
                [
                    'text' => 'Competencias',
                    'url'  => 'competencias',
                    'icon' => 'fas fa-cog fa-xs',
                    'can'  => 'ver-configuracion-secciones',
                    'classes' => 'rounded-xl',
                ],

                [
                    'text' => 'Preguntas',
                    'url'  => 'preguntas',
                    'icon' => 'fas fa-cog fa-xs',
                    'can'  => 'ver-configuracion-preguntas',
                    'classes' => 'rounded-xl',
                ],

                [
                    'text' => 'Estados de Planes de accion',
                    'url'  => 'estados-de-plan-de-accion',
                    'icon' => 'fas fa-cog fa-xs',
                    'can'  => 'ver-estados-de-plan-de-accion',
                    'classes' => 'rounded-xl',
                ],
                                
                [
                    'text' => 'Ojetivos Precargados',
                    'url'  => 'objetivos-precargados',
                    'icon' => 'fas fa-cog fa-xs',
                    'can'  => 'ver-objetivos-precargados',
                    'classes' => 'rounded-xl',
                ],

                [
                    'text' => 'Escalas de medicion',
                    'url'  => 'escala_mediciones',
                    'icon' => 'fas fa-cog fa-xs',
                    'can'  => 'ver-escala-medicion',
                    'classes' => 'rounded-xl',
                ],

                [
                    'text' => 'Tipos de medicion',
                    'url'  => 'tipo_mediciones',
                    'icon' => 'fas fa-cog fa-xs',
                    'can'  => 'ver-tipo-medicion',
                    'classes' => 'rounded-xl',
                ],

                [
                    'text' => 'Tipos de Competencias',
                    'url'  => 'tipo_competencias',
                    'icon' => 'fas fa-cog fa-xs',
                    'can'  => 'ver-tipo-competencia',
                    'classes' => 'rounded-xl',
                ],

                [
                    'text' => 'Conf. Campaña Competencias',
                    'url'  => 'campania_has_competencias',
                    'icon' => 'fas fa-cog fa-xs',
                    'can'  => 'ver-campania-has-competencia',
                    'classes' => 'rounded-xl',
                ],

                [
                    'text' => 'Niveles Jerárquicos',
                    'url'  => 'nivel_jerarquicos',
                    'icon' => 'fas fa-sitemap',
                    'can'  => 'ver-nivel-jerarquico',
                    'classes' => 'rounded-xl',
                ],
                
                [
                    'text' => 'Grados',
                    'url'  => 'grados',
                    'icon' => 'fas fa-sort-numeric-up', // Puedes cambiar el icono
                    'can'  => 'ver-grado',
                    'classes' => 'rounded-xl',
                ],

                [
                    'text' => 'Dominios',
                    'url'  => 'dominios',
                    'icon' => 'fas fa-th-large', // Puedes cambiar el icono
                    'can'  => 'ver-dominio',
                    'classes' => 'rounded-xl',
                ],

                [
                    'text' => 'Tipos de Puesto',
                    'url'  => 'tipo_de_puestos',
                    'icon' => 'fas fa-briefcase', // Puedes cambiar el icono
                    'can'  => 'ver-tipo-de-puesto',
                    'classes' => 'rounded-xl',
                ],                

                [
                    'text' => 'Conf. Campaña Puestos',
                    'url'  => 'tipo_puesto_niveles',
                    'icon' => 'fas fa-cog fa-xs',
                    'can'  => 'ver-tipo-de-puesto-has-nivel-jerarquico',
                    'classes' => 'rounded-xl',
                ],

                [
                    'text' => 'Conf. Campaña puestos.',
                    'url'  => 'campania_has_objetivos',
                    'icon' => 'fas fa-cog fa-xs',
                    'can'  => 'ver-campania-has-objetivo',
                    'classes' => 'rounded-xl',
                ],

                [
                    'text' => 'Dominio Pregunta',
                    'url'  => 'dominio-has-preguntas',
                    'icon' => 'fas fa-link',
                    'can'  => 'ver-dominio-pregunta',
                    'classes' => 'rounded-xl',
                ],

                // [
                //     'text' => 'Opciones',
                //     'url'  => 'opciones',
                //     'icon' => 'fas fa-cog fa-xs',
                //     'can'  => 'ver-modalidad',
                //     'classes' => 'rounded-xl',
                // ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '/css/select2.min.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@11',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => true,
];
