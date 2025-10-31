<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{

    public function dashboard()
    {

        $stats = [
            'total_users' => DB::table('users')->where('active', 1)->count(),
            'total_rewards' => DB::table('rewards')->where('active', 1)->count(),
            'pending_redemptions' => DB::table('reward_redemptions')->where('status', 'pending')->count(),
            'total_points_distributed' => DB::table('point_transactions')
                ->where('type', 'earned')
                ->sum('points')
        ];
        

        $recentUsers = DB::table('users')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $recentRedemptions = DB::table('reward_redemptions')
            ->join('users', 'reward_redemptions.user_id', '=', 'users.user_id')
            ->join('rewards', 'reward_redemptions.reward_id', '=', 'rewards.reward_id')
            ->select('reward_redemptions.*', 'users.first_name', 'users.last_name', 'rewards.title')
            ->orderBy('reward_redemptions.created_at', 'desc')
            ->limit(10)
            ->get();
        
        $topRewards = DB::table('reward_redemptions')
            ->join('rewards', 'reward_redemptions.reward_id', '=', 'rewards.reward_id')
            ->select('rewards.title', DB::raw('COUNT(*) as total_redemptions'))
            ->groupBy('rewards.reward_id', 'rewards.title')
            ->orderBy('total_redemptions', 'desc')
            ->limit(5)
            ->get();
        
        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentRedemptions', 'topRewards'));
    }
}
