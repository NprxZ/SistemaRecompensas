<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{

    public function index()
    {
        $stats = [
            'total_users' => DB::table('users')->where('active', 1)->count(),
            'total_rewards' => DB::table('rewards')->where('active', 1)->count(),
            'total_redemptions' => DB::table('reward_redemptions')->count(),
            'total_points_distributed' => DB::table('point_transactions')
                ->where('type', 'earned')
                ->sum('points'),
            'total_points_redeemed' => DB::table('point_transactions')
                ->where('type', 'redeemed')
                ->sum('points')
        ];
        
        return view('admin.reportes.index', compact('stats'));
    }
    

    public function usuarios(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        

        $newUsers = DB::table('users')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->count();
        

        $topUsersByPoints = DB::table('users')
            ->where('active', 1)
            ->orderBy('points', 'desc')
            ->limit(10)
            ->get();
        
        $topUsersByRedemptions = DB::table('users')
            ->join('reward_redemptions', 'users.user_id', '=', 'reward_redemptions.user_id')
            ->select('users.*', DB::raw('COUNT(reward_redemptions.redemption_id) as total_redemptions'))
            ->where('users.active', 1)
            ->groupBy('users.user_id')
            ->orderBy('total_redemptions', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.reportes.usuarios', compact(
            'newUsers',
            'topUsersByPoints',
            'topUsersByRedemptions',
            'dateFrom',
            'dateTo'
        ));
    }
    

    public function recompensas(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        

        $topRewards = DB::table('rewards')
            ->join('reward_redemptions', 'rewards.reward_id', '=', 'reward_redemptions.reward_id')
            ->select(
                'rewards.*',
                DB::raw('COUNT(reward_redemptions.redemption_id) as total_redemptions'),
                DB::raw('SUM(reward_redemptions.points_used) as total_points_used')
            )
            ->whereBetween('reward_redemptions.created_at', [$dateFrom, $dateTo])
            ->groupBy('rewards.reward_id')
            ->orderBy('total_redemptions', 'desc')
            ->limit(10)
            ->get();
        
        $byCategory = DB::table('rewards')
            ->join('reward_redemptions', 'rewards.reward_id', '=', 'reward_redemptions.reward_id')
            ->select(
                'rewards.category',
                DB::raw('COUNT(reward_redemptions.redemption_id) as total_redemptions')
            )
            ->whereBetween('reward_redemptions.created_at', [$dateFrom, $dateTo])
            ->groupBy('rewards.category')
            ->get();
        
        return view('admin.reportes.recompensas', compact(
            'topRewards',
            'byCategory',
            'dateFrom',
            'dateTo'
        ));
    }
    
    public function puntos(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        
        $pointsEarned = DB::table('point_transactions')
            ->where('type', 'earned')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->sum('points');
        
        $pointsRedeemed = abs(DB::table('point_transactions')
            ->where('type', 'redeemed')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->sum('points'));
        
        $byType = DB::table('point_transactions')
            ->select('type', DB::raw('COUNT(*) as total'), DB::raw('SUM(points) as total_points'))
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('type')
            ->get();
        
        $topActivities = DB::table('activities')
            ->join('user_activities', 'activities.activity_id', '=', 'user_activities.activity_id')
            ->select(
                'activities.*',
                DB::raw('COUNT(user_activities.user_activity_id) as total_completions'),
                DB::raw('SUM(user_activities.points_earned) as total_points_awarded')
            )
            ->whereBetween('user_activities.created_at', [$dateFrom, $dateTo])
            ->groupBy('activities.activity_id')
            ->orderBy('total_completions', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.reportes.puntos', compact(
            'pointsEarned',
            'pointsRedeemed',
            'byType',
            'topActivities',
            'dateFrom',
            'dateTo'
        ));
    }
}