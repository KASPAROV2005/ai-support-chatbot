<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Message;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::query();

        // 🔎 Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('visitor_id', 'like', '%' . $request->search . '%')
                  ->orWhere('subject', 'like', '%' . $request->search . '%');
            });
        }

        //  Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()->paginate(10)->withQueryString();

        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $messages = collect();

        if ($ticket->conversation_id) {
            $messages = Message::where('conversation_id', $ticket->conversation_id)
                ->orderBy('id')
                ->get();
        }

        return view('admin.tickets.show', compact('ticket', 'messages'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        if (!$ticket->conversation_id) {
            return back()->with('error', 'Ce ticket n’a pas de conversation liée.');
        }

        Message::create([
            'conversation_id' => $ticket->conversation_id,
            'role' => 'bot',
            'content' => $request->message,
        ]);

        // optional: keep it open
        if ($ticket->status === 'resolved') {
            $ticket->update(['status' => 'in_progress']);
        }

        return back()->with('success', 'Réponse envoyée ✅');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved',
        ]);

        $ticket->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Ticket status updated ✅');
    }
}
