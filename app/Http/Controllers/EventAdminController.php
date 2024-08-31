<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventAdminController extends Controller
{
    // Method to display the events management page
    public function index()
    {
        return view('events'); // Make sure this view exists in your resources/views directory as events.blade.php
    }

    // Method to fetch events data for the admin page or FullCalendar if needed
    public function fetchEventsData()
    {
        $events = Event::select('id', 'title', 'start_date as start', 'end_date as end', 'description', 'is_active')->get();
        return response()->json($events);
    }

    // Store a new event
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'is_active' => 'required|integer',
        ]);

        Event::create($request->all());
        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    // Update an existing event
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'is_active' => 'required|integer',
        ]);

        $event = Event::findOrFail($id);
        $event->update($request->all());
        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    // Delete an existing event
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }

    public function show($id)
    {
        try {
            $event = Event::findOrFail($id);
            // Ensure start and end dates are correctly formatted as 'Y-m-d\TH:i'
            $event->start = date('Y-m-d\TH:i', strtotime($event->start_date));
            $event->end = date('Y-m-d\TH:i', strtotime($event->end_date));
            return response()->json($event);
        } catch (\Exception $e) {
            \Log::error('Error fetching event: ' . $e->getMessage());
            return response()->json(['error' => 'Event not found.'], 404);
        }
    }
    
}
