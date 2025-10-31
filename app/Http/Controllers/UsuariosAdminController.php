<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsuariosAdminController extends Controller
{

    public function index(Request $request)
    {
        $query = DB::table('users');
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('plate_number', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->has('active') && $request->active != 'all') {
            $query->where('active', $request->active);
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.usuarios.index', compact('users'));
    }
    
    public function create()
    {
        return view('admin.usuarios.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:100|unique:users,email',
            'plate_number' => 'required|string|max:20|unique:users,plate_number',
            'phone' => 'nullable|string|max:20',
            'vehicle_brand' => 'required|string|max:50',
            'vehicle_model' => 'required|string|max:50',
            'vehicle_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'points' => 'nullable|integer|min:0'
        ]);
        
        try {
            $tempPassword = \Illuminate\Support\Str::random(8);
            
            DB::table('users')->insert([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'plate_number' => strtoupper(trim($request->plate_number)),
                'password' => Hash::make($tempPassword),
                'phone' => $request->phone,
                'vehicle_brand' => $request->vehicle_brand,
                'vehicle_model' => $request->vehicle_model,
                'vehicle_year' => $request->vehicle_year,
                'points' => $request->points ?? 0,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return redirect()->route('admin.usuarios.index')
                ->with('success', 'Usuario creado correctamente. Contraseña temporal: ' . $tempPassword);
            
        } catch (\Exception $e) {
            Log::error('Error al crear usuario: ' . $e->getMessage());
            return back()->with('error', 'Error al crear el usuario')->withInput();
        }
    }
    
    public function show($userId)
    {
        $user = DB::table('users')->where('user_id', $userId)->first();
        
        if (!$user) {
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'Usuario no encontrado');
        }
        
        $transactions = DB::table('point_transactions')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $redemptions = DB::table('reward_redemptions')
            ->join('rewards', 'reward_redemptions.reward_id', '=', 'rewards.reward_id')
            ->where('reward_redemptions.user_id', $userId)
            ->select('reward_redemptions.*', 'rewards.title')
            ->orderBy('reward_redemptions.created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.usuarios.show', compact('user', 'transactions', 'redemptions'));
    }
    
    public function edit($userId)
    {
        $user = DB::table('users')->where('user_id', $userId)->first();
        
        if (!$user) {
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'Usuario no encontrado');
        }
        
        return view('admin.usuarios.edit', compact('user'));
    }
    

    public function update(Request $request, $userId)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:100|unique:users,email,' . $userId . ',user_id',
            'phone' => 'nullable|string|max:20',
            'vehicle_brand' => 'required|string|max:50',
            'vehicle_model' => 'required|string|max:50',
            'vehicle_year' => 'required|integer|min:1900|max:' . (date('Y') + 1)
        ]);
        
        try {
            DB::table('users')
                ->where('user_id', $userId)
                ->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'vehicle_brand' => $request->vehicle_brand,
                    'vehicle_model' => $request->vehicle_model,
                    'vehicle_year' => $request->vehicle_year,
                    'updated_at' => now()
                ]);
            
            return redirect()->route('admin.usuarios.show', $userId)
                ->with('success', 'Usuario actualizado correctamente');
            
        } catch (\Exception $e) {
            Log::error('Error al actualizar usuario: ' . $e->getMessage());
            return back()->with('error', 'Error al actualizar el usuario')->withInput();
        }
    }
    

    public function destroy($userId)
    {
        try {
            DB::table('users')
                ->where('user_id', $userId)
                ->update([
                    'deleted_at' => now(),
                    'active' => 0
                ]);
            
            return redirect()->route('admin.usuarios.index')
                ->with('success', 'Usuario eliminado correctamente');
            
        } catch (\Exception $e) {
            Log::error('Error al eliminar usuario: ' . $e->getMessage());
            return back()->with('error', 'Error al eliminar el usuario');
        }
    }
    

    public function toggleActive($userId)
    {
        try {
            $user = DB::table('users')->where('user_id', $userId)->first();
            
            DB::table('users')
                ->where('user_id', $userId)
                ->update([
                    'active' => !$user->active,
                    'updated_at' => now()
                ]);
            
            $status = !$user->active ? 'activado' : 'desactivado';
            
            return back()->with('success', "Usuario {$status} correctamente");
            
        } catch (\Exception $e) {
            Log::error('Error al cambiar estado del usuario: ' . $e->getMessage());
            return back()->with('error', 'Error al cambiar el estado del usuario');
        }
    }
    

    public function addPoints(Request $request, $userId)
    {
        $request->validate([
            'points' => 'required|integer',
            'description' => 'required|string|max:255'
        ]);
        
        try {
            DB::beginTransaction();
            
            $points = $request->points;
            
            if ($points > 0) {
                DB::table('users')->where('user_id', $userId)->increment('points', $points);
            } else {
                DB::table('users')->where('user_id', $userId)->decrement('points', abs($points));
            }
            
            DB::table('point_transactions')->insert([
                'user_id' => $userId,
                'type' => $points > 0 ? 'earned' : 'adjusted',
                'points' => $points,
                'description' => $request->description,
                'admin_id' => \Illuminate\Support\Facades\Session::get('admin_id'),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::commit();
            
            return back()->with('success', 'Puntos agregados correctamente');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al agregar puntos: ' . $e->getMessage());
            return back()->with('error', 'Error al agregar puntos');
        }
    }
}