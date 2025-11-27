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
            'total_users' => DB::table('usuarios')->where('activo', 1)->count(),
            'total_rewards' => DB::table('recompensas')->where('activo', 1)->count(),
            'pending_redemptions' => DB::table('canjes_recompensas')->where('estado', 'pendiente')->count(),
            'total_points_distributed' => DB::table('transacciones_puntos')
                ->where('tipo', 'ganado')
                ->sum('puntos')
        ];
        

        $recentUsers = DB::table('usuarios')
            ->orderBy('fecha_creacion', 'desc')
            ->limit(5)
            ->get();
        
        $recentRedemptions = DB::table('canjes_recompensas')
            ->join('usuarios', 'canjes_recompensas.usuario_id', '=', 'usuarios.usuario_id')
            ->join('recompensas', 'canjes_recompensas.recompensa_id', '=', 'recompensas.recompensa_id')
            ->select('canjes_recompensas.*', 'usuarios.nombre', 'usuarios.apellido', 'recompensas.titulo')
            ->orderBy('canjes_recompensas.fecha_creacion', 'desc')
            ->limit(10)
            ->get();
        
        $topRewards = DB::table('canjes_recompensas')
            ->join('recompensas', 'canjes_recompensas.recompensa_id', '=', 'recompensas.recompensa_id')
            ->select('recompensas.titulo', DB::raw('COUNT(*) as total_redemptions'))
            ->groupBy('recompensas.recompensa_id', 'recompensas.titulo')
            ->orderBy('total_redemptions', 'desc')
            ->limit(5)
            ->get();
        
        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentRedemptions', 'topRewards'));
    }
}