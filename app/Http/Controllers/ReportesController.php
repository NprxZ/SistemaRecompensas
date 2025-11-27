<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{

    public function index()
    {
        $stats = [
            'total_users' => DB::table('usuarios')->where('activo', 1)->count(),
            'total_rewards' => DB::table('recompensas')->where('activo', 1)->count(),
            'total_redemptions' => DB::table('canjes_recompensas')->count(),
            'total_points_distributed' => DB::table('transacciones_puntos')
                ->where('tipo', 'ganado')
                ->sum('puntos'),
            'total_points_redeemed' => abs(DB::table('transacciones_puntos')
                ->where('tipo', 'canjeado')
                ->sum('puntos'))
        ];
        
        return view('admin.reportes.index', compact('stats'));
    }
    

    public function usuarios(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        

        $newUsers = DB::table('usuarios')
            ->whereBetween('fecha_creacion', [$dateFrom, $dateTo])
            ->count();
        

        $topUsersByPoints = DB::table('usuarios')
            ->where('activo', 1)
            ->orderBy('puntos', 'desc')
            ->limit(10)
            ->get();
        
        $topUsersByRedemptions = DB::table('usuarios')
            ->join('canjes_recompensas', 'usuarios.usuario_id', '=', 'canjes_recompensas.usuario_id')
            ->select('usuarios.*', DB::raw('COUNT(canjes_recompensas.canje_id) as total_redemptions'))
            ->where('usuarios.activo', 1)
            ->groupBy('usuarios.usuario_id', 'usuarios.nombre', 'usuarios.apellido', 'usuarios.email', 
                      'usuarios.numero_placa', 'usuarios.password', 'usuarios.telefono', 
                      'usuarios.marca_vehiculo', 'usuarios.modelo_vehiculo', 'usuarios.anio_vehiculo',
                      'usuarios.puntos', 'usuarios.activo', 'usuarios.ultimo_acceso',
                      'usuarios.fecha_creacion', 'usuarios.fecha_actualizacion', 'usuarios.fecha_eliminacion')
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
        

        $topRewards = DB::table('recompensas')
            ->join('canjes_recompensas', 'recompensas.recompensa_id', '=', 'canjes_recompensas.recompensa_id')
            ->select(
                'recompensas.*',
                DB::raw('COUNT(canjes_recompensas.canje_id) as total_redemptions'),
                DB::raw('SUM(canjes_recompensas.puntos_utilizados) as total_points_used')
            )
            ->whereBetween('canjes_recompensas.fecha_creacion', [$dateFrom, $dateTo])
            ->groupBy('recompensas.recompensa_id', 'recompensas.titulo', 'recompensas.descripcion',
                      'recompensas.puntos_requeridos', 'recompensas.imagen', 'recompensas.categoria',
                      'recompensas.inventario', 'recompensas.activo', 'recompensas.fecha_expiracion',
                      'recompensas.terminos_condiciones', 'recompensas.fecha_creacion', 
                      'recompensas.fecha_actualizacion', 'recompensas.fecha_eliminacion')
            ->orderBy('total_redemptions', 'desc')
            ->limit(10)
            ->get();
        
        $byCategory = DB::table('recompensas')
            ->join('canjes_recompensas', 'recompensas.recompensa_id', '=', 'canjes_recompensas.recompensa_id')
            ->select(
                'recompensas.categoria',
                DB::raw('COUNT(canjes_recompensas.canje_id) as total_redemptions')
            )
            ->whereBetween('canjes_recompensas.fecha_creacion', [$dateFrom, $dateTo])
            ->groupBy('recompensas.categoria')
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
        
        $pointsEarned = DB::table('transacciones_puntos')
            ->where('tipo', 'ganado')
            ->whereBetween('fecha_creacion', [$dateFrom, $dateTo])
            ->sum('puntos');
        
        $pointsRedeemed = abs(DB::table('transacciones_puntos')
            ->where('tipo', 'canjeado')
            ->whereBetween('fecha_creacion', [$dateFrom, $dateTo])
            ->sum('puntos'));
        
        $byType = DB::table('transacciones_puntos')
            ->select('tipo', DB::raw('COUNT(*) as total'), DB::raw('SUM(puntos) as total_points'))
            ->whereBetween('fecha_creacion', [$dateFrom, $dateTo])
            ->groupBy('tipo')
            ->get();
        
        $topActivities = DB::table('actividades')
            ->join('actividades_usuarios', 'actividades.actividad_id', '=', 'actividades_usuarios.actividad_id')
            ->select(
                'actividades.*',
                DB::raw('COUNT(actividades_usuarios.actividad_usuario_id) as total_completions'),
                DB::raw('SUM(actividades_usuarios.puntos_ganados) as total_points_awarded')
            )
            ->whereBetween('actividades_usuarios.fecha_creacion', [$dateFrom, $dateTo])
            ->groupBy('actividades.actividad_id', 'actividades.nombre', 'actividades.descripcion',
                      'actividades.puntos_otorgados', 'actividades.tipo', 'actividades.activo',
                      'actividades.fecha_creacion', 'actividades.fecha_actualizacion')
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