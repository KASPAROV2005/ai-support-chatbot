<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900">Tickets</h2>
                <p class="text-sm text-slate-600">Support • suivi • statuts</p>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <form method="GET" class="flex flex-col sm:flex-row gap-3">
                <input name="search" value="{{ request('search') }}"
                       class="w-full sm:w-80 rounded-xl border border-slate-200 px-4 py-2 bg-white"
                       placeholder="Search visitor_id / subject..." />

                <select name="status" class="w-full sm:w-56 rounded-xl border border-slate-200 px-4 py-2 bg-white">
                    <option value="">All status</option>
                    <option value="open" @selected(request('status')==='open')>open</option>
                    <option value="in_progress" @selected(request('status')==='in_progress')>in_progress</option>
                    <option value="resolved" @selected(request('status')==='resolved')>resolved</option>
                </select>

                <button class="rounded-xl bg-slate-900 text-white px-5 py-2 font-semibold">Filter</button>
            </form>

            <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-100 text-slate-600 text-sm">
                        <tr>
                            <th class="p-4">ID</th>
                            <th class="p-4">Visitor</th>
                            <th class="p-4">Subject</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Priority</th>
                            <th class="p-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($tickets as $t)
                            <tr class="hover:bg-slate-50">
                                <td class="p-4 font-semibold">{{ $t->id }}</td>
                                <td class="p-4 text-slate-700">{{ $t->visitor_id }}</td>
                                <td class="p-4 text-slate-900 font-semibold">{{ $t->subject }}</td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        @class([
                                            'bg-emerald-100 text-emerald-800' => $t->status==='open',
                                            'bg-amber-100 text-amber-800' => $t->status==='in_progress',
                                            'bg-slate-200 text-slate-800' => $t->status==='resolved',
                                        ])">
                                        {{ $t->status }}
                                    </span>
                                </td>
                                <td class="p-4">{{ $t->priority }}</td>
                                <td class="p-4">
                                    <a class="text-indigo-600 font-semibold hover:underline"
                                       href="{{ route('admin.tickets.show', $t) }}">
                                        Open
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        @if($tickets->count() === 0)
                            <tr>
                                <td colspan="6" class="p-6 text-center text-slate-500">No tickets found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div>
                {{ $tickets->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
