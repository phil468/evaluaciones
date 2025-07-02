<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Show the form for creating feedback.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear()
    {
        // Aquí puedes implementar la lógica para mostrar el formulario de feedback
        return view('feedback.crear');
    }
}
