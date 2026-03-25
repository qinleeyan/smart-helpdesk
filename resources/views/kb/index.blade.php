<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Knowledge Base') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="backdrop-blur-md bg-white/10 dark:bg-gray-900/50 border border-white/20 dark:border-gray-700/50 shadow-xl overflow-hidden sm:rounded-lg p-8">
                
                <div class="text-center max-w-2xl mx-auto mb-10">
                    <h3 class="text-3xl font-extrabold text-white mb-4">How can we help you?</h3>
                    <form method="GET" action="{{ route('kb.index') }}" class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search for troubleshooting guides, tutorials, and fixes..." class="w-full bg-gray-800/80 border border-purple-500/50 rounded-full text-white pl-5 pr-12 py-4 focus:ring-2 focus:ring-purple-500 outline-none shadow-lg">
                        <button type="submit" class="absolute right-3 top-3.5 text-gray-400 hover:text-purple-400 transition">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </form>
                </div>

                @if(Auth::user()->role === 'admin')
                <div class="mb-8 flex justify-end">
                    <a href="{{ route('kb.admin.index') }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition border border-gray-600 text-sm">
                        Manage Articles (Admin)
                    </a>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($articles as $article)
                        <a href="{{ route('kb.show', $article) }}" class="block group">
                            <div class="h-full bg-gray-800/40 border border-gray-700/50 rounded-xl p-6 hover:bg-gray-800/60 hover:border-purple-500/50 transition-all duration-300 shadow-sm relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-blue-400 to-purple-500 opacity-0 group-hover:opacity-100 transition"></div>
                                <h4 class="text-xl font-bold text-gray-200 mb-2 group-hover:text-purple-300 transition">{{ $article->title }}</h4>
                                <p class="text-sm text-gray-500 mb-4">{{ Str::limit(strip_tags($article->content), 100) }}</p>
                                <div class="flex justify-between items-center text-xs text-gray-600">
                                    <span>{{ $article->category->name ?? 'General' }}</span>
                                    <span>{{ $article->views }} views</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <p class="text-lg">No articles found matching your search.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
