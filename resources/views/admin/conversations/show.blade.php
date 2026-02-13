<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900">
                    Conversation #{{ $conversation->id }}
                </h2>
                <p class="text-sm text-slate-600">
                    Visitor: {{ $conversation->visitor_id }}
                </p>
            </div>

            <a href="{{ route('admin.conversations.index') }}"
               class="text-sm font-semibold text-indigo-600 hover:underline">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-3 text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-4">
                <div class="font-bold text-slate-900 mb-3">Messages</div>

                <div class="space-y-3 max-h-[520px] overflow-auto pr-2">
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
                        <div class="text-slate-500">No messages yet.</div>
                    @endforelse
                </div>
            </div>

            <form method="POST" action="{{ route('admin.conversations.reply', $conversation) }}"
      class="rounded-2xl bg-white border border-slate-200 shadow-sm p-4 flex gap-3">
    @csrf

    <input name="message"
           class="flex-1 rounded-xl border border-slate-200 px-3 py-2"
           placeholder="Write a support reply..." />

    <button type="submit"
            class="rounded-xl bg-indigo-600 text-white px-5 py-2 font-semibold">
        Send
    </button>
</form>

{{-- show validation errors --}}
@if ($errors->any())
    <div class="mt-3 rounded-xl bg-rose-50 border border-rose-200 p-3 text-rose-800">
        @foreach ($errors->all() as $e)
            <div>{{ $e }}</div>
        @endforeach
    </div>
@endif

        </div>
    </div>
</x-app-layout>
