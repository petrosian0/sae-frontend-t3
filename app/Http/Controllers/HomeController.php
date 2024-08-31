<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch events related to the logged-in user via the event_user pivot table
        $events = DB::table('events')
            ->join('event_user', 'events.id', '=', 'event_user.event_id')
            ->where('event_user.user_id', auth()->id())
            ->select('events.*') // Select all columns from the events table
            ->get();

        // Pass the events data to the view
        return view('home', compact('events'));
    }
}

