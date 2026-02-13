<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900">Conversations</h2>
                <p class="text-sm text-slate-600">Inbox support • chat threads</p>
            </div>

            <a href="{{ route('admin.dashboard') }}"
               class="text-sm font-semibold text-indigo-600 hover:underline">
                ← Dashboard
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <form method="GET" class="flex gap-3">
                <input name="search" value="{{ request('search') }}"
                       class="w-full sm:w-80 rounded-xl border border-slate-200 px-4 py-2 bg-white"
                       placeholder="Search visitor_id..." />
                <button class="rounded-xl bg-slate-900 text-white px-5 py-2 font-semibold">Search</button>
            </form>

            <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-100 text-slate-600 text-sm">
                        <tr>
                            <th class="p-4">ID</th>
                            <th class="p-4">Visitor</th>
                            <th class="p-4">Site</th>
                            <th class="p-4">Created</th>
                            <th class="p-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($conversations as $c)
                            <tr class="hover:bg-slate-50">
                                <td class="p-4 font-semibold">{{ $c->id }}</td>
                                <td class="p-4 text-slate-900 font-semibold">{{ $c->visitor_id }}</td>
                                <td class="p-4 text-slate-600">{{ $c->site_id }}</td>
                                <td class="p-4 text-slate-600">{{ $c->created_at->diffForHumans() }}</td>
                                <td class="p-4">
                                    <a class="text-indigo-600 font-semibold hover:underline"
                                       href="{{ route('admin.conversations.show', $c) }}">
                                        Open
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        @if($conversations->count() === 0)
                            <tr>
                                <td colspan="5" class="p-6 text-center text-slate-500">No conversations.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div>
                {{ $conversations->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
