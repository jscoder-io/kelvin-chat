<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Order
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('order');
    }
}
