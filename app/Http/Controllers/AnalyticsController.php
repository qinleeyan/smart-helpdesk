<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized. Admin access required.');
        }

        // Monthly or weekly ticket volume
        // To keep it simple for SQLite and MySQL compatibility, we just fetch all and group in PHP for MVP.
        $tickets = Ticket::with('category')->get();

        $statusCounts = [
            'Open' => $tickets->where('status', 'Open')->count(),
            'In Progress' => $tickets->where('status', 'In Progress')->count(),
            'Resolved' => $tickets->where('status', 'Resolved')->count(),
        ];

        // Group by category
        $categoryCounts = [];
        foreach ($tickets as $t) {
            $catName = $t->category->name ?? 'Uncategorized';
            if (!isset($categoryCounts[$catName])) {
                $categoryCounts[$catName] = 0;
            }
            $categoryCounts[$catName]++;
        }

        // Group by Date
        $dateCounts = [];
        foreach ($tickets as $t) {
            $date = $t->created_at->format('Y-m-d');
            if (!isset($dateCounts[$date])) {
                $dateCounts[$date] = 0;
            }
            $dateCounts[$date]++;
        }

        return Inertia::render('Analytics/Index', [
            'statusCounts' => $statusCounts,
            'categoryCounts' => $categoryCounts,
            'dateCounts' => $dateCounts
        ]);
    }
}
