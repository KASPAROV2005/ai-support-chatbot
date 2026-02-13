<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900">Ticket #{{ $ticket->id }}</h2>
                <p class="text-sm text-slate-600">{{ $ticket->subject }} • {{ $ticket->visitor_id }}</p>
            </div>
            <a href="{{ route('admin.tickets.index') }}" class="text-sm font-semibold text-indigo-600 hover:underline">← Back</a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-3 text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-xl bg-rose-50 border border-rose-200 p-3 text-rose-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="lg:col-span-2 rounded-2xl bg-white border border-slate-200 shadow-sm p-4">
                    <div class="font-bold text-slate-900 mb-3">Conversation</div>

                    <div class="space-y-3 max-h-[420px] overflow-auto pr-2">
                        @forelse($messages as $m)
                            <div class="flex {{ $m->role === 'support' ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[80%] rounded-2xl px-3 py-2
                                    {{ $m->role === 'support' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-900' }}">
                                    <div class="text-[11px] opacity-70 mb-1">
                                        {{ $m->role }} • {{ $m->created_at }}
                                    </div>
                                    <div class="whitespace-pre-wrap">{{ $m->content }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-slate-500">No messages.</div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-4 space-y-4">
                    <div>
                        <div class="text-sm text-slate-600">Status</div>

                        <form method="POST" action="{{ route('admin.tickets.status', $ticket) }}" class="mt-2 flex gap-2">
                            @csrf
                            <select name="status" class="flex-1 rounded-xl border border-slate-200 px-3 py-2">
                                <option value="open" @selected($ticket->status==='open')>open</option>
                                <option value="in_progress" @selected($ticket->status==='in_progress')>in_progress</option>
                                <option value="resolved" @selected($ticket->status==='resolved')>resolved</option>
                            </select>
                            <button class="rounded-xl bg-indigo-600 text-white px-4 py-2 font-semibold">Save</button>
                        </form>
                    </div>

                    <div>
                        <div class="text-sm text-slate-600">Priority</div>
                        <div class="font-bold text-slate-900">{{ $ticket->priority }}</div>
                    </div>

                    <div>
                        <div class="text-sm text-slate-600">Description</div>
                        <div class="text-slate-900 whitespace-pre-wrap">{{ $ticket->description }}</div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}"
                  class="rounded-2xl bg-white border border-slate-200 shadow-sm p-4 flex gap-3">
                @csrf
                <input name="message" class="flex-1 rounded-xl border border-slate-200 px-3 py-2"
                       placeholder="Write a support reply..." />
                <button class="rounded-xl bg-slate-900 text-white px-5 py-2 font-semibold">
                    Send
                </button>
            </form>

        </div>
    </div>
</x-app-layout>
