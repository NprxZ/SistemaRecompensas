<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActividadesAdminController extends Controller
{

    public function index()
    {
        $activities = DB::table('activities')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.actividades.index', compact('activities'));
    }
    
    public function create()
    {
        return view('admin.actividades.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'points_awarded' => 'required|integer|min:1',
            'type' => 'required|in:purchase,referral,review,visit,other'
        ]);
        
        try {
            DB::table('activities')->insert([
                'name' => $request->name,
                'description' => $request->description,
                'points_awarded' => $request->points_awarded,
                'type' => $request->type,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return redirect()->route('admin.actividades.index')
                ->with('success', 'Actividad creada correctamente');
            
        } catch (\Exception $e) {
            Log::error('Error al crear actividad: ' . $e->getMessage());
            return back()->with('error', 'Error al crear la actividad')->withInput();
        }
    }
    
    public function show($activityId)
    {
        $activity = DB::table('activities')->where('activity_id', $activityId)->first();
        
        if (!$activity) {
            return redirect()->route('admin.actividades.index')
                ->with('error', 'Actividad no encontrada');
        }
        
        $stats = [
            'total_completions' => DB::table('user_activities')
                ->where('activity_id', $activityId)
                ->count(),
            'total_points_awarded' => DB::table('user_activities')
                ->where('activity_id', $activityId)
                ->sum('points_earned'),
            'unique_users' => DB::table('user_activities')
                ->where('activity_id', $activityId)
                ->distinct('user_id')
                ->count('user_id')
        ];
        
        return view('admin.actividades.show', compact('activity', 'stats'));
    }
    
    public function edit($activityId)
    {
        $activity = DB::table('activities')->where('activity_id', $activityId)->first();
        
        if (!$activity) {
            return redirect()->route('admin.actividades.index')
                ->with('error', 'Actividad no encontrada');
        }
        
        return view('admin.actividades.edit', compact('activity'));
    }
    
    public function update(Request $request, $activityId)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'points_awarded' => 'required|integer|min:1',
            'type' => 'required|in:purchase,referral,review,visit,other'
        ]);
        
        try {
            DB::table('activities')
                ->where('activity_id', $activityId)
                ->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'points_awarded' => $request->points_awarded,
                    'type' => $request->type,
                    'updated_at' => now()
                ]);
            
            return redirect()->route('admin.actividades.show', $activityId)
                ->with('success', 'Actividad actualizada correctamente');
            
        } catch (\Exception $e) {
            Log::error('Error al actualizar actividad: ' . $e->getMessage());
            return back()->with('error', 'Error al actualizar la actividad')->withInput();
        }
    }
    
    public function toggleActive($activityId)
    {
        try {
            $activity = DB::table('activities')->where('activity_id', $activityId)->first();
            
            DB::table('activities')
                ->where('activity_id', $activityId)
                ->update([
                    'active' => !$activity->active,
                    'updated_at' => now()
                ]);
            
            $status = !$activity->active ? 'activada' : 'desactivada';
            
            return back()->with('success', "Actividad {$status} correctamente");
            
        } catch (\Exception $e) {
            Log::error('Error al cambiar estado de actividad: ' . $e->getMessage());
            return back()->with('error', 'Error al cambiar el estado');
        }
    }
}