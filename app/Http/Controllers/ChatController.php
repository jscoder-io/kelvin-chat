<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Chat
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request, int $id)
    {
        return view('chat')
            ->with('id', $id);
    }
}
