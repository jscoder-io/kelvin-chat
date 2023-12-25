<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $shops = Shop::latest();
        if (auth()->user()->role > 1) {
            $shops->whereIn('id', auth()->user()->shop);
        }

        return view('dashboard')
            ->with('shops', $shops->get());
    }
}
