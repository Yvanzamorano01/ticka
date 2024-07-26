<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        $events_count = Event::count();
        $tickets_sold = Ticket::count();
        $total_revenue = Ticket::sum('price');

        return view('dashboard.dashboard', compact('events_count', 'tickets_sold', 'total_revenue'));
    }
}
