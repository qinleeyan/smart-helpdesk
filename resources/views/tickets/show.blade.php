<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight flex items-center justify-between">
            <span>{{ __('Ticket Details: #') }}{{ $ticket->id }}</span>
            <a href="{{ route('tickets.index') }}"
                class="text-sm px-3 py-1 bg-gray-700 hover:bg-gray-600 border border-gray-600 text-gray-300 rounded transition">Back
                to List</a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Left Column: Details -->
            <div class="md:col-span-2 space-y-6">
                <div
                    class="backdrop-blur-md bg-white/10 dark:bg-gray-900/50 border border-white/20 dark:border-gray-700/50 shadow-xl overflow-hidden sm:rounded-lg p-6">
                    <h3 class="text-2xl font-bold text-white mb-2">{{ $ticket->subject }}</h3>
                    <div
                        class="flex items-center space-x-4 text-sm text-gray-400 mb-6 border-b border-gray-700/50 pb-4">
                        <span>By: <strong class="text-gray-300">{{ $ticket->user->name }}</strong></span>
                        <span>Category: <strong
                                class="text-gray-300">{{ $ticket->category->name ?? 'N/A' }}</strong></span>
                        <span>Created: <strong
                                class="text-gray-300">{{ $ticket->created_at->format('M d, Y H:i') }}</strong></span>
                    </div>

                    <div class="prose prose-invert max-w-none text-gray-300 whitespace-pre-wrap">
                        {{ $ticket->description }}
                    </div>
                </div>

                <!-- Activity Log -->
                <div
                    class="backdrop-blur-md bg-white/10 dark:bg-gray-900/50 border border-white/20 dark:border-gray-700/50 shadow-xl overflow-hidden sm:rounded-lg p-6">
                    <h4 class="text-lg font-bold text-white mb-4">Activity Log</h4>
                    <div class="space-y-4">
                        @foreach($ticket->activityLogs as $log)
                            <div class="flex items-start space-x-3 text-sm">
                                <div
                                    class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-gray-300 font-bold shrink-0">
                                    {{ substr($log->user->name, 0, 1) }}
                                </div>
                                <div class="flex-1 bg-gray-800/50 p-3 rounded-lg border border-gray-700/50">
                                    <div class="flex justify-between items-center mb-1">
                                        <strong class="text-gray-200">{{ $log->user->name }}</strong>
                                        <span class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-400">{{ $log->details }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column: Status & Admin Actions -->
            <div class="space-y-6">
                <div
                    class="backdrop-blur-md bg-white/10 dark:bg-gray-900/50 border border-white/20 dark:border-gray-700/50 shadow-xl overflow-hidden sm:rounded-lg p-6">
                    <h4 class="text-lg font-bold text-white mb-4">Status & Priority</h4>

                    @if(session('success'))
                        <div
                            class="mb-4 px-3 py-2 bg-emerald-500/20 border border-emerald-500/50 text-emerald-200 rounded text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(Auth::user()->role === 'admin')
                        <form method="POST" action="{{ route('tickets.update', $ticket) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-400 mb-1">Update Status</label>
                                <select name="status"
                                    class="w-full bg-gray-800/50 border border-gray-700 rounded text-white p-2 focus:ring-purple-500 text-sm">
                                    <option value="Open" {{ $ticket->status === 'Open' ? 'selected' : '' }}>Open</option>
                                    <option value="In Progress" {{ $ticket->status === 'In Progress' ? 'selected' : '' }}>In
                                        Progress</option>
                                    <option value="Resolved" {{ $ticket->status === 'Resolved' ? 'selected' : '' }}>Resolved
                                    </option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-400 mb-1">Update Priority</label>
                                <select name="priority"
                                    class="w-full bg-gray-800/50 border border-gray-700 rounded text-white p-2 focus:ring-purple-500 text-sm">
                                    <option value="Low" {{ $ticket->priority === 'Low' ? 'selected' : '' }}>Low</option>
                                    <option value="Normal" {{ $ticket->priority === 'Normal' ? 'selected' : '' }}>Normal
                                    </option>
                                    <option value="Urgent" {{ $ticket->priority === 'Urgent' ? 'selected' : '' }}>Urgent
                                    </option>
                                </select>
                            </div>
                            <button type="submit"
                                class="w-full py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded shadow hover:opacity-90 transition text-sm font-semibold">
                                Save Changes
                            </button>
                        </form>
                    @else
                        <div class="space-y-4">
                            <div>
                                <span class="block text-xs text-gray-500 uppercase tracking-wider mb-1">Current
                                    Status</span>
                                <span
                                    class="inline-block px-3 py-1 text-sm rounded-full 
                                        {{ $ticket->status === 'Open' ? 'bg-blue-500/20 text-blue-300 border border-blue-500/20' : ($ticket->status === 'In Progress' ? 'bg-purple-500/20 text-purple-300 border border-purple-500/20' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/20') }}">
                                    {{ $ticket->status }}
                                </span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 uppercase tracking-wider mb-1">Current
                                    Priority</span>
                                <span
                                    class="inline-block px-3 py-1 text-sm rounded-full 
                                        {{ $ticket->priority === 'Urgent' ? 'bg-red-500/20 text-red-300 border border-red-500/20' : ($ticket->priority === 'Normal' ? 'bg-blue-500/20 text-blue-300 border border-blue-500/20' : 'bg-gray-500/20 text-gray-300 border border-gray-500/20') }}">
                                    {{ $ticket->priority }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>