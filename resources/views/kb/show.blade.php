<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            <a href="{{ route('kb.index') }}" class="text-purple-400 hover:text-purple-300">Knowledge Base</a>
            <span class="text-gray-500 mx-2">/</span>
            {{ $article->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div
                class="backdrop-blur-md bg-white/10 dark:bg-gray-900/50 border border-white/20 dark:border-gray-700/50 shadow-xl overflow-hidden sm:rounded-lg p-8 text-gray-200">
                <h1
                    class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500 mb-4">
                    {{ $article->title }}</h1>
                <div class="flex items-center space-x-4 text-sm text-gray-500 mb-8 border-b border-gray-700 pb-4">
                    <span>Category: {{ $article->category->name ?? 'General' }}</span>
                    <span>Views: {{ $article->views }}</span>
                    <span>Last Updated: {{ $article->updated_at->format('M d, Y') }}</span>
                </div>
                <div class="prose prose-xl prose-invert max-w-none text-gray-300 whitespace-pre-wrap">
                    {!! nl2br(e($article->content)) !!}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>