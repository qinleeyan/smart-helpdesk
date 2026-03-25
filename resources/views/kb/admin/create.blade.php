<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Create KB Article') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div
                class="backdrop-blur-md bg-white/10 dark:bg-gray-900/50 border border-white/20 dark:border-gray-700/50 shadow-xl overflow-hidden sm:rounded-lg p-6">
                <form method="POST" action="{{ route('kb.admin.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-300 mb-1">Title</label>
                        <input type="text" name="title" required
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg text-white p-2.5 focus:ring-purple-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Category</label>
                            <select name="category_id" required
                                class="w-full bg-gray-800 border border-gray-700 rounded-lg text-white p-2.5 focus:ring-purple-500">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Keywords (for Auto
                                Suggest)</label>
                            <input type="text" name="keywords" placeholder="e.g. wifi, error, network, slow"
                                class="w-full bg-gray-800 border border-gray-700 rounded-lg text-white p-2.5 focus:ring-purple-500">
                            <p class="text-xs text-gray-500 mt-1">Comma separated words helps the real-time search.</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-1">Content</label>
                        <textarea name="content" rows="10" required
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg text-white p-2.5 focus:ring-purple-500"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-6 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-bold rounded-lg shadow hover:opacity-90 transition">
                            Publish Article
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>