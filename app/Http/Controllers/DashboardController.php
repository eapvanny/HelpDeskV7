<?php

namespace App\Http\Controllers;

use App\Http\Helpers\AppHelper;
use Illuminate\Http\Request;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        $query = Ticket::query();

        // Check if the authenticated user is an admin
        if (auth()->user()->role_id !== AppHelper::USER_SUPER_ADMIN && AppHelper::USER_ADMIN) {
            $query->where('user_id', auth()->id());
        }

        // Count tickets based on status
        $openTickets = (clone $query)->where('status_id', AppHelper::STATUS_OPEN)->count();
        $pendingTickets = (clone $query)->where('status_id', AppHelper::STATUS_PENDING)->count();
        $resolvedTickets = (clone $query)->where('status_id', AppHelper::STATUS_RESOLVED)->count();
        $closedTickets = (clone $query)->where('status_id', AppHelper::STATUS_CLOSED)->count();

        // Fetch the count of tickets opened per month
        $monthlyTickets = (clone $query)
            ->selectRaw('EXTRACT(MONTH FROM created_at) as month, COUNT(id) as count')
            ->whereYear('created_at', date('Y')) // Filter for the current year
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Convert data to match the months of the year
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = $monthlyTickets[$i] ?? 0; // If no data for a month, default to 0
        }

        return view('backend.dashboard', compact('openTickets', 'pendingTickets', 'resolvedTickets', 'closedTickets', 'monthlyData'));
    }



    public function getTicketData()
    {
        $monthlyTickets = Ticket::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $ticketData = [
            'open' => Ticket::where('status', 'open')->count(),
            'pending' => Ticket::where('status', 'pending')->count(),
            'solved' => Ticket::where('status', 'solved')->count(),
            'unassigned' => Ticket::whereNull('agent_id')->count(),
            'monthly' => array_fill(0, 12, 0)
        ];

        foreach ($monthlyTickets as $month => $count) {
            $ticketData['monthly'][$month - 1] = $count;
        }

        return response()->json($ticketData);
    }
}
