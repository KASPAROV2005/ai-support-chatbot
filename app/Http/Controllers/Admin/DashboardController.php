<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Conversation;
use App\Models\Message;

class DashboardController extends Controller
{
    public function index()
    {
        $ticketsTotal = Ticket::count();
        $ticketsOpen = Ticket::where('status', 'open')->count();
        $ticketsInProgress = Ticket::where('status', 'in_progress')->count();
        $ticketsResolved = Ticket::where('status', 'resolved')->count();

        $conversationsTotal = Conversation::count();
        $messagesToday = Message::whereDate('created_at', today())->count();

        $latestTickets = Ticket::latest()->take(6)->get();
        $latestConversations = Conversation::latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'ticketsTotal',
            'ticketsOpen',
            'ticketsInProgress',
            'ticketsResolved',
            'conversationsTotal',
            'messagesToday',
            'latestTickets',
            'latestConversations'
        ));
    }
}
