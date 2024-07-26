<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\TicketType;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'event_date' => 'required|date',
            'location' => 'required',
            'ticket_types.*.name' => 'required',
            'ticket_types.*.price' => 'required|numeric',
        ]);

        $event = Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'location' => $request->location,
            'organizer_id' => auth()->id(),
        ]);

        foreach ($request->ticket_types as $ticketType) {
            TicketType::create([
                'event_id' => $event->id,
                'name' => $ticketType['name'],
                'price' => $ticketType['price'],
            ]);
        }

        return redirect()->route('events.index')->with('success', 'Event created successfully!');
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $ticketTypes = $event->ticketTypes;
        return view('events.edit', compact('event', 'ticketTypes'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'event_date' => 'required|date',
            'location' => 'required',
            'ticket_types.*.name' => 'required',
            'ticket_types.*.price' => 'required|numeric',
        ]);

        $event->update([
            'name' => $request->name,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'location' => $request->location,
        ]);

        // Supprimer les anciens types de billets et ajouter les nouveaux
        $event->ticketTypes()->delete();
        foreach ($request->ticket_types as $ticketType) {
            TicketType::create([
                'event_id' => $event->id,
                'name' => $ticketType['name'],
                'price' => $ticketType['price'],
            ]);
        }

        return redirect()->route('events.index')->with('success', 'Event updated successfully');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
    }
}
