<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecompensasAdminController extends Controller
{

    public function index(Request $request)
    {
        $query = DB::table('rewards');
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('title', 'LIKE', "%{$search}%");
        }
        
        if ($request->has('category') && $request->category != 'all') {
            $query->where('category', $request->category);
        }
        
        if ($request->has('active') && $request->active != 'all') {
            $query->where('active', $request->active);
        }
        
        $rewards = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $categories = DB::table('rewards')->distinct()->pluck('category');
        
        return view('admin.recompensas.index', compact('rewards', 'categories'));
    }
    
    public function create()
    {
        return view('admin.recompensas.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'points_required' => 'required|integer|min:1',
            'category' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'expiration_date' => 'nullable|date',
            'terms_conditions' => 'nullable|string'
        ]);
        
        try {
            $imagePath = null;
            
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('rewards', 'public');
            }
            
            DB::table('rewards')->insert([
                'title' => $request->title,
                'description' => $request->description,
                'points_required' => $request->points_required,
                'category' => $request->category,
                'stock' => $request->stock,
                'image' => $imagePath,
                'expiration_date' => $request->expiration_date,
                'terms_conditions' => $request->terms_conditions,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return redirect()->route('admin.recompensas.index')
                ->with('success', 'Recompensa creada correctamente');
            
        } catch (\Exception $e) {
            Log::error('Error al crear recompensa: ' . $e->getMessage());
            return back()->with('error', 'Error al crear la recompensa')->withInput();
        }
    }
    
    public function show($rewardId)
    {
        $reward = DB::table('rewards')->where('reward_id', $rewardId)->first();
        
        if (!$reward) {
            return redirect()->route('admin.recompensas.index')
                ->with('error', 'Recompensa no encontrada');
        }
        
        $stats = [
            'total_redemptions' => DB::table('reward_redemptions')
                ->where('reward_id', $rewardId)
                ->count(),
            'pending_redemptions' => DB::table('reward_redemptions')
                ->where('reward_id', $rewardId)
                ->where('status', 'pending')
                ->count(),
            'total_points_used' => DB::table('reward_redemptions')
                ->where('reward_id', $rewardId)
                ->sum('points_used')
        ];
        
        return view('admin.recompensas.show', compact('reward', 'stats'));
    }
    
    public function edit($rewardId)
    {
        $reward = DB::table('rewards')->where('reward_id', $rewardId)->first();
        
        if (!$reward) {
            return redirect()->route('admin.recompensas.index')
                ->with('error', 'Recompensa no encontrada');
        }
        
        return view('admin.recompensas.edit', compact('reward'));
    }
    
    public function update(Request $request, $rewardId)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'points_required' => 'required|integer|min:1',
            'category' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'expiration_date' => 'nullable|date',
            'terms_conditions' => 'nullable|string'
        ]);
        
        try {
            $updateData = [
                'title' => $request->title,
                'description' => $request->description,
                'points_required' => $request->points_required,
                'category' => $request->category,
                'stock' => $request->stock,
                'expiration_date' => $request->expiration_date,
                'terms_conditions' => $request->terms_conditions,
                'updated_at' => now()
            ];
            
            if ($request->hasFile('image')) {
                $updateData['image'] = $request->file('image')->store('rewards', 'public');
            }
            
            DB::table('rewards')
                ->where('reward_id', $rewardId)
                ->update($updateData);
            
            return redirect()->route('admin.recompensas.show', $rewardId)
                ->with('success', 'Recompensa actualizada correctamente');
            
        } catch (\Exception $e) {
            Log::error('Error al actualizar recompensa: ' . $e->getMessage());
            return back()->with('error', 'Error al actualizar la recompensa')->withInput();
        }
    }
    
    public function toggleActive($rewardId)
    {
        try {
            $reward = DB::table('rewards')->where('reward_id', $rewardId)->first();
            
            DB::table('rewards')
                ->where('reward_id', $rewardId)
                ->update([
                    'active' => !$reward->active,
                    'updated_at' => now()
                ]);
            
            $status = !$reward->active ? 'activada' : 'desactivada';
            
            return back()->with('success', "Recompensa {$status} correctamente");
            
        } catch (\Exception $e) {
            Log::error('Error al cambiar estado de la recompensa: ' . $e->getMessage());
            return back()->with('error', 'Error al cambiar el estado');
        }
    }
}