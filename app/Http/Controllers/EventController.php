<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    // Fetch events for the calendar
    public function fetchEvents()
    {
        // Fetch events from the database
        $events = Event::select('id', 'title', 'start_date as start', 'end_date as end', 'description')
                        ->where('is_active', 1) // Optionally filter only active events
                        ->get();

        // Convert the collection to an array and return
        return response()->json($events);
    }

    // Show the calendar page
    public function index()
    {
        return view('calendar');
    }

    // Store a new event
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            $event = Event::create($validatedData);

            EventUser::create([
                'event_id' => $event->id,
                'user_id' => auth()->id(),
            ]);

            return response()->json($event, 201);
        } catch (\Exception $e) {
            Log::error('Event creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create event', 'message' => $e->getMessage()], 500);
        }
    }

    // Update an existing event
    public function update(Request $request, $id)
    {
        // Log the incoming request data to help debug if any fields are missing
        Log::info('Update request data:', $request->all());

        // Validate the incoming request to ensure required fields are not null
        $validatedData = $request->validate([
            'title' => 'required|string|max:255', // Ensure title is present and is a string
            'start_date' => 'required|date',     // Validate start_date is a valid date
            'end_date' => 'required|date',       // Validate end_date is a valid date
            'description' => 'nullable|string',  // Optional field for description
            'is_active' => 'nullable|boolean',   // Optional field for active status
        ]);

        // Find the event by ID
        $event = Event::find($id);

        // Check if the event exists
        if (!$event) {
            return response()->json(['error' => 'Event not found.'], 404);
        }

        try {
            // Attempt to update the event with validated data
            $event->update($validatedData);

            // Return the updated event data
            return response()->json($event);
        } catch (\Exception $e) {
            // Log error if update fails
            Log::error('Event update failed: ' . $e->getMessage());

            // Return a response indicating the update failed
            return response()->json(['error' => 'Failed to update event', 'message' => $e->getMessage()], 500);
        }
    }

    // Delete an existing event
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return response()->json(null, 204);
    }
}
