<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Shop
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('shop');
    }
}
