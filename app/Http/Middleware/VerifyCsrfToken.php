<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'categoria/*',
        '/categoria/actualizar',
        '/categoria/desactivar',
        '/categoria/activar',

        'articulo/*',
        '/articulo/actualizar',
        '/articulo/desactivar',
        '/articulo/activar',

        'cliente/*',
        '/cliente/actualizar',
        '/cliente/registrar',
        '/cliente/desactivar',
        '/cliente/activar',

        'proveedor/*',
        '/proveedor/actualizar',
        'rol/*',
        '/rol/selectRol',
        
        'user/*',
        '/user/registrar',
        '/user/actualizar',
        '/user/desactivar',
        '/user/activar',
    ];
}
