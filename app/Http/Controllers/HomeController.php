<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Load latest public events that have a thumbnail to display in the homepage carousel
        $events = \App\Models\Event::where('is_public', true)
            ->whereNotNull('thumbnail')
            ->orderBy('start_date', 'desc')
            ->take(6)
            ->get();

        return view('home', compact('events'))->layout('layouts.app');
    }
}
