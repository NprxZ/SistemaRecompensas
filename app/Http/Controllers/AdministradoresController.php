<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdministradoresController extends Controller
{

    public function index()
    {
        $administrators = DB::table('administrators')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.administradores.index', compact('administrators'));
    }
    
    public function create()
    {
        return view('admin.administradores.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:100|unique:administrators,email',
            'username' => 'required|string|max:50|unique:administrators,username',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:super_admin,admin,moderator'
        ]);
        
        try {
            DB::table('administrators')->insert([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role' => $request->role,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return redirect()->route('admin.administradores.index')
                ->with('success', 'Administrador creado correctamente');
            
        } catch (\Exception $e) {
            Log::error('Error al crear administrador: ' . $e->getMessage());
            return back()->with('error', 'Error al crear el administrador')->withInput();
        }
    }
    

    public function show($adminId)
    {
        $administrator = DB::table('administrators')
            ->where('admin_id', $adminId)
            ->whereNull('deleted_at')
            ->first();
        
        if (!$administrator) {
            return redirect()->route('admin.administradores.index')
                ->with('error', 'Administrador no encontrado');
        }
        
        return view('admin.administradores.show', compact('administrator'));
    }
    
    public function edit($adminId)
    {
        $administrator = DB::table('administrators')
            ->where('admin_id', $adminId)
            ->whereNull('deleted_at')
            ->first();
        
        if (!$administrator) {
            return redirect()->route('admin.administradores.index')
                ->with('error', 'Administrador no encontrado');
        }
        
        return view('admin.administradores.edit', compact('administrator'));
    }
    
    public function update(Request $request, $adminId)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:100|unique:administrators,email,' . $adminId . ',admin_id',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:super_admin,admin,moderator',
            'password' => 'nullable|string|min:6'
        ]);
        
        try {
            $updateData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => $request->role,
                'updated_at' => now()
            ];
            
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }
            
            DB::table('administrators')
                ->where('admin_id', $adminId)
                ->update($updateData);
            
            return redirect()->route('admin.administradores.show', $adminId)
                ->with('success', 'Administrador actualizado correctamente');
            
        } catch (\Exception $e) {
            Log::error('Error al actualizar administrador: ' . $e->getMessage());
            return back()->with('error', 'Error al actualizar el administrador')->withInput();
        }
    }
    
    public function destroy($adminId)
    {
        try {
            DB::table('administrators')
                ->where('admin_id', $adminId)
                ->update([
                    'deleted_at' => now(),
                    'active' => 0
                ]);
            
            return redirect()->route('admin.administradores.index')
                ->with('success', 'Administrador eliminado correctamente');
            
        } catch (\Exception $e) {
            Log::error('Error al eliminar administrador: ' . $e->getMessage());
            return back()->with('error', 'Error al eliminar el administrador');
        }
    }
    
    public function toggleActive($adminId)
    {
        try {
            $admin = DB::table('administrators')->where('admin_id', $adminId)->first();
            
            DB::table('administrators')
                ->where('admin_id', $adminId)
                ->update([
                    'active' => !$admin->active,
                    'updated_at' => now()
                ]);
            
            $status = !$admin->active ? 'activado' : 'desactivado';
            
            return back()->with('success', "Administrador {$status} correctamente");
            
        } catch (\Exception $e) {
            Log::error('Error al cambiar estado del administrador: ' . $e->getMessage());
            return back()->with('error', 'Error al cambiar el estado');
        }
    }
}