<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Template
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('template');
    }
}
