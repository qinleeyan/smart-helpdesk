<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTickets = Ticket::count();
        $openTickets = Ticket::where('status', 'Open')->count();
        $inProgressTickets = Ticket::where('status', 'In Progress')->count();
        $resolvedTickets = Ticket::where('status', 'Resolved')->count();
        $totalArticles = Article::count();

        // Category breakdown
        $categoryCounts = Category::withCount('tickets')->get()->map(fn($c) => [
            'name' => $c->name,
            'tickets' => $c->tickets_count,
        ]);

        // Daily ticket trend (last 14 days)
        $dailyTrend = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dailyTrend[] = [
                'date' => now()->subDays($i)->format('M d'),
                'tickets' => Ticket::whereDate('created_at', $date)->count(),
            ];
        }

        // Recent tickets
        $recentTickets = Ticket::with(['category', 'user'])
            ->latest()
            ->take(8)
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => [
                'totalTickets' => $totalTickets,
                'openTickets' => $openTickets,
                'inProgressTickets' => $inProgressTickets,
                'resolvedTickets' => $resolvedTickets,
                'totalArticles' => $totalArticles,
                'resolveRate' => $totalTickets > 0 ? round(($resolvedTickets / $totalTickets) * 100, 1) : 0,
            ],
            'categoryCounts' => $categoryCounts,
            'dailyTrend' => $dailyTrend,
            'recentTickets' => $recentTickets,
        ]);
    }
}
