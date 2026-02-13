<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        $q = Conversation::query();

        if ($request->search) {
            $q->where('visitor_id', 'like', '%'.$request->search.'%');
        }

        $conversations = $q->latest()->paginate(15)->withQueryString();

        return view('admin.conversations.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $messages = Message::where('conversation_id', $conversation->id)
            ->orderBy('id')
            ->get();

        return view('admin.conversations.show', compact('conversation', 'messages'));
    }

    public function reply(Request $request, Conversation $conversation)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'role' => 'support',
            'content' => $request->message,
        ]);

        return back()->with('success', 'Message support envoyé ✅');
    }
}
