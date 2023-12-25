<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * User
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('user');
    }
}
