<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $tickets = Ticket::with(['user', 'category', 'assignee'])->latest()->paginate(10);
        } else {
            $tickets = Ticket::where('user_id', $user->id)->with('category')->latest()->paginate(10);
        }
        return Inertia::render('Tickets/Index', ['tickets' => $tickets]);
    }

    public function export()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $fileName = 'tickets_report_' . date('Y-m-d') . '.csv';
        $tickets = Ticket::with(['user', 'category'])->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'Subject', 'Category', 'Status', 'Priority', 'User', 'Created At'];

        $callback = function () use ($tickets, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->id,
                    $ticket->subject,
                    $ticket->category->name ?? 'N/A',
                    $ticket->status,
                    $ticket->priority,
                    $ticket->user->name ?? 'Unknown',
                    $ticket->created_at->format('Y-m-d H:i:s')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        $categories = Category::all();
        return Inertia::render('Tickets/Create', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:Low,Normal,Urgent',
        ]);

        $ticket = Auth::user()->tickets()->create($validated);

        $ticket->activityLogs()->create([
            'user_id' => Auth::id(),
            'action' => 'created',
            'details' => 'Ticket created.'
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket submitted successfully!');
    }

    public function show(Ticket $ticket)
    {
        if (Auth::user()->role !== 'admin' && $ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->load(['activityLogs.user', 'category', 'user', 'assignee']);
        return Inertia::render('Tickets/Show', ['ticket' => $ticket]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:Open,In Progress,Resolved',
            'priority' => 'required|in:Low,Normal,Urgent',
        ]);

        $ticket->update($validated);

        $ticket->activityLogs()->create([
            'user_id' => Auth::id(),
            'action' => 'updated',
            'details' => "Status changed to {$validated['status']}, Priority: {$validated['priority']}"
        ]);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket updated successfully.');
    }
}
