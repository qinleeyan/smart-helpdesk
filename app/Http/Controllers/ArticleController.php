<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ArticleController extends Controller
{
    // Public KB
    public function kb(Request $request)
    {
        $query = Article::with('category');

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where('title', 'like', "%{$searchTerm}%")
                ->orWhere('keywords', 'like', "%{$searchTerm}%");
        }

        $articles = $query->latest()->paginate(12);
        return Inertia::render('KB/Index', ['articles' => $articles]);
    }

    public function show(Article $article)
    {
        $article->increment('views');
        $article->load('category');
        return Inertia::render('KB/Show', ['article' => $article]);
    }

    // AI Suggestion API
    public function suggest(Request $request)
    {
        $query = $request->get('q');
        if (!$query)
            return response()->json([]);

        $articles = Article::where('title', 'like', "%{$query}%")
            ->orWhere('keywords', 'like', "%{$query}%")
            ->orWhere('content', 'like', "%{$query}%")
            ->select('id', 'title', 'slug')
            ->limit(3)
            ->get();

        return response()->json($articles);
    }

    // ==== ADMIN CRUD ====
    public function adminIndex()
    {
        if (Auth::user()->role !== 'admin')
            abort(403);
        $articles = Article::with('category')->latest()->paginate(10);
        return view('kb.admin.index', compact('articles'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'admin')
            abort(403);
        $categories = Category::all();
        return view('kb.admin.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'keywords' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();

        Article::create($validated);

        return redirect()->route('kb.admin.index')->with('success', 'Article created successfully!');
    }
}
