<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Site;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Ticket;

class ChatController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'site_key'   => 'required|string',
            'visitor_id' => 'required|string',
            'message'    => 'required|string',
        ]);

        $message   = trim($request->message);
        $visitorId = $request->visitor_id;

        $site = Site::where('site_key', $request->site_key)
            ->where('is_active', true)
            ->firstOrFail();

        $conversation = Conversation::firstOrCreate([
            'site_id'    => $site->id,
            'visitor_id' => $visitorId,
        ]);

        // Save user message
        Message::create([
            'conversation_id' => $conversation->id,
            'role'            => 'user',
            'content'         => $message,
        ]);

        // ✅ If waiting for ticket confirmation
        if (($conversation->ticket_offer_pending ?? false) === true) {

            if ($this->isYes($message)) {
                $ticket = Ticket::create([
                    'site_id'         => $site->id,
                    'conversation_id' => $conversation->id,
                    'visitor_id'      => $visitorId,
                    'subject'         => $conversation->ticket_draft_subject ?? 'Demande de support',
                    'description'     => $conversation->ticket_draft_description ?? $message,
                    'status'          => 'open',
                    'priority'        => 'medium',
                ]);

                $conversation->update([
                    'ticket_offer_pending'     => false,
                    'ticket_draft_subject'     => null,
                    'ticket_draft_description' => null,
                ]);

                $reply = "✅ Ticket créé (ID: {$ticket->id}). Un agent support va vous répondre ici.";

                $botMsg = Message::create([
                    'conversation_id' => $conversation->id,
                    'role'            => 'bot',
                    'content'         => $reply,
                ]);

                return response()->json([
                    'reply'   => $reply,
                    'last_id' => $botMsg->id,
                ]);
            }

            if ($this->isNo($message)) {
                $conversation->update([
                    'ticket_offer_pending'     => false,
                    'ticket_draft_subject'     => null,
                    'ticket_draft_description' => null,
                ]);

                $reply = "D'accord 👍 Pas de ticket. Expliquez-moi plus et je vous aide ici.";

                $botMsg = Message::create([
                    'conversation_id' => $conversation->id,
                    'role'            => 'bot',
                    'content'         => $reply,
                ]);

                return response()->json([
                    'reply'   => $reply,
                    'last_id' => $botMsg->id,
                ]);
            }

            $reply = "Je n’ai pas compris 😅 Répondez par **oui** pour créer un ticket, ou **non** pour continuer sans ticket.";

            $botMsg = Message::create([
                'conversation_id' => $conversation->id,
                'role'            => 'bot',
                'content'         => $reply,
            ]);

            return response()->json([
                'reply'   => $reply,
                'last_id' => $botMsg->id,
            ]);
        }

        // ✅ AI reply (OpenRouter) بدل mockAI
        $reply = $this->openRouterAIReply($message, $conversation);

        // Offer ticket if it looks like a real issue
        if ($this->shouldOfferTicket($message)) {
            $conversation->update([
                'ticket_offer_pending'     => true,
                'ticket_draft_subject'     => 'Demande de support (via chat)',
                'ticket_draft_description' => $message,
            ]);

            $reply .= "\n\n🧾 Voulez-vous créer un ticket de support ? (oui/non)";
        }

        $botMsg = Message::create([
            'conversation_id' => $conversation->id,
            'role'            => 'bot',
            'content'         => $reply,
        ]);

        return response()->json([
            'reply'   => $reply,
            'last_id' => $botMsg->id,
        ]);
    }

    // ✅ Poll new messages for the visitor
    public function poll(Request $request)
    {
        $request->validate([
            'site_key'   => 'required|string',
            'visitor_id' => 'required|string',
            'after_id'   => 'nullable|integer',
        ]);

        $site = Site::where('site_key', $request->site_key)
            ->where('is_active', true)
            ->firstOrFail();

        $conversation = Conversation::where('site_id', $site->id)
            ->where('visitor_id', $request->visitor_id)
            ->first();

        if (!$conversation) {
            return response()->json(['messages' => []]);
        }

        $afterId = (int) ($request->after_id ?? 0);

        $messages = Message::where('conversation_id', $conversation->id)
            ->where('id', '>', $afterId)
            ->orderBy('id')
            ->get(['id', 'role', 'content', 'created_at']);

        return response()->json([
            'messages' => $messages,
        ]);
    }

    /**
     * ✅ OpenRouter AI Reply (with history)
     */
    private function openRouterAIReply(string $userMessage, Conversation $conversation): string
    {
        $apiKey = config('services.openrouter.key');
        $model  = config('services.openrouter.model', 'mistralai/mistral-7b-instruct');

        if (!$apiKey) {
            return "⚠️ OPENROUTER_API_KEY missing in .env";
        }

        // last 10 messages for context
        $last = Message::where('conversation_id', $conversation->id)
            ->orderBy('id', 'desc')
            ->take(10)
            ->get()
            ->reverse();

        $messages = [
            [
                'role' => 'system',
                'content' =>
                    "Tu es un assistant support professionnel pour une plateforme SaaS. "
                    . "Réponds en français clair, court et utile. "
                    . "Si le client a un vrai problème (connexion, bug, paiement, compte), propose la création de ticket."
            ]
        ];

        foreach ($last as $m) {
            $messages[] = [
                'role' => $m->role === 'user' ? 'user' : 'assistant',
                'content' => $m->content,
            ];
        }

        $messages[] = ['role' => 'user', 'content' => $userMessage];

        try {
            $res = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'HTTP-Referer'  => config('app.url', 'http://localhost'),
                'X-Title'       => config('app.name', 'Laravel'),
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => $model,
                'messages' => $messages,
                'temperature' => 0.6,
                'max_tokens' => 400,
            ]);

            if (!$res->ok()) {
                return "❌ OpenRouter error ({$res->status()}): " . substr($res->body(), 0, 250);
            }

            return data_get($res->json(), 'choices.0.message.content', '🤖 (empty reply)');
        } catch (\Throwable $e) {
            return "❌ AI exception: " . $e->getMessage();
        }
    }

    private function isYes(string $text): bool
    {
        $t = mb_strtolower(trim($text));
        return in_array($t, ['oui','yes','y','ok','okay',"daccord","d'accord",'نعم','ايوا','واخا'], true);
    }

    private function isNo(string $text): bool
    {
        $t = mb_strtolower(trim($text));
        return in_array($t, ['non','no','n','لا','nope','مابغيتش'], true);
    }

    private function shouldOfferTicket(string $message): bool
    {
        $t = mb_strtolower($message);
        $keywords = [
            'problème','probleme','bug','erreur','error','issue',
            'bloqué','blocked','connexion','login','mot de passe','compte',
            'paiement','payment','urgent','marche pas','ne fonctionne pas'
        ];

        foreach ($keywords as $k) {
            if (str_contains($t, $k)) return true;
        }
        return false;
    }
}
