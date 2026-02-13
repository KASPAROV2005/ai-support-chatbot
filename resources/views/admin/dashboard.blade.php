<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900">Admin Dashboard</h2>
                <p class="text-sm text-slate-600">Tickets • Conversations • Support</p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.tickets.index') }}"
                   class="px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-semibold">
                    Tickets
                </a>
                <a href="{{ url('/admin/conversations') }}"
                   class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-semibold">
                    Conversations
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- KPI cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-4">
                <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm xl:col-span-2">
                    <div class="text-sm text-slate-600">Tickets (total)</div>
                    <div class="text-3xl font-black text-slate-900">{{ $ticketsTotal }}</div>
                </div>

                <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                    <div class="text-sm text-slate-600">Open</div>
                    <div class="text-3xl font-black text-emerald-600">{{ $ticketsOpen }}</div>
                </div>

                <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                    <div class="text-sm text-slate-600">In progress</div>
                    <div class="text-3xl font-black text-amber-600">{{ $ticketsInProgress }}</div>
                </div>

                <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                    <div class="text-sm text-slate-600">Resolved</div>
                    <div class="text-3xl font-black text-slate-700">{{ $ticketsResolved }}</div>
                </div>

                <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                    <div class="text-sm text-slate-600">Messages (today)</div>
                    <div class="text-3xl font-black text-indigo-600">{{ $messagesToday }}</div>
                </div>
            </div>

            {{-- Main grid --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                {{-- Latest tickets --}}
                <div class="xl:col-span-2 rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-5 flex items-center justify-between">
                        <div>
                            <div class="text-lg font-extrabold text-slate-900">Latest tickets</div>
                            <div class="text-sm text-slate-600">Dernières demandes de support</div>
                        </div>
                        <a href="{{ route('admin.tickets.index') }}" class="text-sm font-semibold text-indigo-600 hover:underline">
                            View all →
                        </a>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($latestTickets as $t)
                            <div class="p-5 flex items-center justify-between gap-4 hover:bg-slate-50">
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-900 truncate">
                                        #{{ $t->id }} — {{ $t->subject }}
                                    </div>
                                    <div class="text-sm text-slate-600 truncate">
                                        Visitor: {{ $t->visitor_id }} • {{ $t->created_at->diffForHumans() }}
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 shrink-0">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        @class([
                                            'bg-emerald-100 text-emerald-800' => $t->status==='open',
                                            'bg-amber-100 text-amber-800' => $t->status==='in_progress',
                                            'bg-slate-200 text-slate-800' => $t->status==='resolved',
                                        ])">
                                        {{ $t->status }}
                                    </span>

                                    <a class="text-sm font-semibold text-slate-900 hover:underline"
                                       href="{{ route('admin.tickets.show', $t) }}">
                                        Open
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-slate-500">No tickets yet.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Latest conversations --}}
                <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-5">
                        <div class="text-lg font-extrabold text-slate-900">Latest conversations</div>
                        <div class="text-sm text-slate-600">Dernières discussions</div>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($latestConversations as $c)
                            <div class="p-5 hover:bg-slate-50">
                                <div class="font-bold text-slate-900 truncate">
                                    {{ $c->visitor_id }}
                                </div>
                                <div class="text-sm text-slate-600">
                                    {{ $c->created_at->diffForHumans() }}
                                </div>

                                <div class="mt-3">
                                    <a href="{{ url('/admin/conversations/'.$c->id) }}"
                                       class="text-sm font-semibold text-indigo-600 hover:underline">
                                        Open conversation →
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-slate-500">No conversations yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
