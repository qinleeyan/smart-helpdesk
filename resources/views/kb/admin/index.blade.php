<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Manage Knowledge Base') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="backdrop-blur-md bg-white/10 dark:bg-gray-900/50 border border-white/20 dark:border-gray-700/50 shadow-xl overflow-hidden sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-100">Articles</h3>
                    <a href="{{ route('kb.admin.create') }}"
                        class="px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg shadow hover:opacity-90 transition">
                        + New Article
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 px-4 py-3 bg-emerald-500/20 border border-emerald-500/50 text-emerald-200 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-700/50 text-sm uppercase text-gray-400">
                                <th class="p-3">Title</th>
                                <th class="p-3">Category</th>
                                <th class="p-3">Views</th>
                                <th class="p-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($articles as $article)
                                <tr class="border-b border-gray-700/50 hover:bg-white/5 transition text-gray-300">
                                    <td class="p-3 font-medium">{{ $article->title }}</td>
                                    <td class="p-3">{{ $article->category->name ?? 'N/A' }}</td>
                                    <td class="p-3">{{ $article->views }}</td>
                                    <td class="p-3">
                                        <a href="{{ route('kb.show', $article) }}" target="_blank"
                                            class="text-blue-400 hover:underline text-sm">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>