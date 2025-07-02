<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PdiController extends Controller
{
    /**
     * Display the PDI details.
     *
     * @return \Illuminate\Http\Response
     */
    public function detalle()
    {
        // Aquí puedes implementar la lógica para mostrar los detalles del PDI
        return view('pdi.detalle');
    }
}
