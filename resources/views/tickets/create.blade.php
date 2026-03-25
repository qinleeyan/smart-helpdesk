<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Create New Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="ticketForm()">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div
                class="backdrop-blur-md bg-white/10 dark:bg-gray-900/50 border border-white/20 dark:border-gray-700/50 shadow-xl overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('tickets.store') }}">
                        @csrf

                        <!-- Subject & Auto Suggest -->
                        <div class="mb-4 relative">
                            <label class="block text-sm font-medium text-gray-300 mb-1">Subject</label>
                            <input type="text" name="subject" x-model="subject" @input.debounce.500ms="fetchSuggestions"
                                required
                                class="w-full bg-gray-800/50 border border-gray-700 rounded-lg text-white p-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none">
                            @error('subject') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror

                            <!-- AI Suggestion Box -->
                            <div x-show="suggestions.length > 0"
                                class="absolute z-10 w-full mt-2 backdrop-blur-xl bg-gray-800/90 border border-purple-500/50 shadow-2xl rounded-lg overflow-hidden transition-all"
                                style="display: none;">
                                <div
                                    class="px-4 py-2 bg-gradient-to-r from-blue-500/20 to-purple-600/20 border-b border-gray-700 font-semibold text-sm flex items-center text-purple-300">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    AI Suggested Solutions
                                </div>
                                <ul>
                                    <template x-for="item in suggestions" :key="item.id">
                                        <li>
                                            <a :href="'/kb/' + item.slug" target="_blank"
                                                class="block px-4 py-3 hover:bg-white/5 transition border-b border-gray-700/50 last:border-0">
                                                <span class="text-blue-400 hover:text-blue-300 underline font-medium"
                                                    x-text="item.title"></span>
                                            </a>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <!-- Category -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Category</label>
                                <select name="category_id" required
                                    class="w-full bg-gray-800/50 border border-gray-700 rounded-lg text-white p-2.5 focus:ring-2 focus:ring-purple-500">
                                    <option value="" disabled selected>Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Priority -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Priority</label>
                                <select name="priority" required
                                    class="w-full bg-gray-800/50 border border-gray-700 rounded-lg text-white p-2.5 focus:ring-2 focus:ring-purple-500">
                                    <option value="Low">Low</option>
                                    <option value="Normal" selected>Normal</option>
                                    <option value="Urgent">Urgent</option>
                                </select>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-300 mb-1">Description</label>
                            <textarea name="description" rows="5" required
                                class="w-full bg-gray-800/50 border border-gray-700 rounded-lg text-white p-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none"
                                placeholder="Describe your issue in detail..."></textarea>
                            @error('description') <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('tickets.index') }}"
                                class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">Cancel</a>
                            <button type="submit"
                                class="px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg shadow hover:opacity-90 transition">
                                Submit Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function ticketForm() {
            return {
                subject: '',
                suggestions: [],
                fetchSuggestions() {
                    if (this.subject.length < 3) {
                        this.suggestions = [];
                        return;
                    }

                    fetch(`/kb/suggest?q=${encodeURIComponent(this.subject)}`)
                        .then(res => res.json())
                        .then(data => {
                            this.suggestions = data;
                        });
                }
            }
        }
    </script>
</x-app-layout>