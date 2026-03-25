<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Tickets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Glassmorphism Card -->
            <div
                class="backdrop-blur-md bg-white/10 dark:bg-gray-900/50 border border-white/20 dark:border-gray-700/50 shadow-xl overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3
                            class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">
                            Active Tickets
                        </h3>
                        <div class="flex space-x-3">
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('tickets.export') }}"
                                    class="px-4 py-2 bg-emerald-600/80 border border-emerald-500 hover:bg-emerald-500/80 text-white rounded-lg shadow transition flex items-center">
                                    Export CSV
                                </a>
                            @endif
                            <a href="{{ route('tickets.create') }}"
                                class="px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg shadow hover:opacity-90 transition flex items-center">
                                + New Ticket
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div
                            class="mb-4 px-4 py-3 bg-emerald-500/20 border border-emerald-500/50 text-emerald-200 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-700/50 text-sm uppercase tracking-wider text-gray-400">
                                    <th class="p-3">ID</th>
                                    <th class="p-3">Subject</th>
                                    <th class="p-3">Category</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3">Priority</th>
                                    @if(Auth::user()->role === 'admin')
                                        <th class="p-3">User</th>
                                    @endif
                                    <th class="p-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                    <tr class="border-b border-gray-700/50 hover:bg-white/5 transition">
                                        <td class="p-3">#{{ $ticket->id }}</td>
                                        <td class="p-3 font-medium">{{ $ticket->subject }}</td>
                                        <td class="p-3">{{ $ticket->category->name ?? 'N/A' }}</td>
                                        <td class="p-3">
                                            <span
                                                class="px-2 py-1 text-xs rounded-full 
                                                        {{ $ticket->status === 'Open' ? 'bg-blue-500/20 text-blue-300' : ($ticket->status === 'In Progress' ? 'bg-purple-500/20 text-purple-300' : 'bg-emerald-500/20 text-emerald-300') }}">
                                                {{ $ticket->status }}
                                            </span>
                                        </td>
                                        <td class="p-3">
                                            <span
                                                class="px-2 py-1 text-xs rounded-full 
                                                        {{ $ticket->priority === 'Urgent' ? 'bg-red-500/20 text-red-300' : ($ticket->priority === 'Normal' ? 'bg-blue-500/20 text-blue-300' : 'bg-gray-500/20 text-gray-300') }}">
                                                {{ $ticket->priority }}
                                            </span>
                                        </td>
                                        @if(Auth::user()->role === 'admin')
                                            <td class="p-3">{{ $ticket->user->name }}</td>
                                        @endif
                                        <td class="p-3">
                                            <a href="{{ route('tickets.show', $ticket) }}"
                                                class="text-blue-400 hover:text-blue-300 underline text-sm">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="p-6 text-center text-gray-500">No active tickets found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $tickets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>