<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        return view('backend.dashboard');
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
            'monthly' => array_fill(0, 12, 0) // Default 12 months
        ];

        foreach ($monthlyTickets as $month => $count) {
            $ticketData['monthly'][$month - 1] = $count;
        }

        return response()->json($ticketData);
    }
}

