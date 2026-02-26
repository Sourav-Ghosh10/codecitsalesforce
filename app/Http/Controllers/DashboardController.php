<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with role-based data.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get role-specific data
        $stats = $this->getDashboardStats($user);
        
        return view('dashboard', compact('stats'));
    }

    /**
     * Get dashboard statistics based on user role
     */
    private function getDashboardStats($user)
    {
        if ($user->isAdmin()) {
            // Admin sees all data
            return [
                'totalClients' => Client::count(),
                'activeLeads' => Client::where('status', 'Lead')->count(),
                'totalRevenue' => Client::count() * 5000, // Estimated revenue per client
                'pendingTasks' => 24,
                'clientsGrowth' => 12.5,
                'leadsGrowth' => 8.2,
                'revenueGrowth' => 23.1,
            ];
        } elseif ($user->isManager()) {
            // Manager sees team data
            return [
                'totalClients' => Client::count(),
                'activeLeads' => Client::where('status', 'Lead')->count(),
                'totalRevenue' => Client::count() * 5000,
                'pendingTasks' => 24,
                'clientsGrowth' => 12.5,
                'leadsGrowth' => 8.2,
                'revenueGrowth' => 23.1,
            ];
        } else {
            // Agent sees only their own clients
            return [
                'totalClients' => $user->clients()->count(),
                'activeLeads' => $user->clients()->where('status', 'Lead')->count(),
                'totalRevenue' => $user->clients()->count() * 5000,
                'pendingTasks' => 24,
                'clientsGrowth' => 5.2,
                'leadsGrowth' => 3.1,
                'revenueGrowth' => 10.5,
            ];
        }
    }
}
