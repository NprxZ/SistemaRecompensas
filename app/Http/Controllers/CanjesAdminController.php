<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CanjesAdminController extends Controller
{

    public function index(Request $request)
    {
        $query = DB::table('reward_redemptions')
            ->join('users', 'reward_redemptions.user_id', '=', 'users.user_id')
            ->join('rewards', 'reward_redemptions.reward_id', '=', 'rewards.reward_id')
            ->select(
                'reward_redemptions.*',
                'users.first_name',
                'users.last_name',
                'users.email',
                'users.plate_number',
                'rewards.title as reward_title'
            );
        
        if ($request->has('status') && $request->status != 'all') {
            $query->where('reward_redemptions.status', $request->status);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('users.first_name', 'LIKE', "%{$search}%")
                  ->orWhere('users.last_name', 'LIKE', "%{$search}%")
                  ->orWhere('users.plate_number', 'LIKE', "%{$search}%")
                  ->orWhere('reward_redemptions.redemption_code', 'LIKE', "%{$search}%");
            });
        }
        
        $redemptions = $query->orderBy('reward_redemptions.created_at', 'desc')->paginate(20);
        
        $stats = [
            'pending' => DB::table('reward_redemptions')->where('status', 'pending')->count(),
            'approved' => DB::table('reward_redemptions')->where('status', 'approved')->count(),
            'delivered' => DB::table('reward_redemptions')->where('status', 'delivered')->count(),
            'cancelled' => DB::table('reward_redemptions')->where('status', 'cancelled')->count()
        ];
        
        return view('admin.canjes.index', compact('redemptions', 'stats'));
    }
    
    public function show($redemptionId)
    {
        $redemption = DB::table('reward_redemptions')
            ->join('users', 'reward_redemptions.user_id', '=', 'users.user_id')
            ->join('rewards', 'reward_redemptions.reward_id', '=', 'rewards.reward_id')
            ->leftJoin('administrators', 'reward_redemptions.approved_by', '=', 'administrators.admin_id')
            ->where('reward_redemptions.redemption_id', $redemptionId)
            ->select(
                'reward_redemptions.*',
                'users.first_name as user_first_name',
                'users.last_name as user_last_name',
                'users.email as user_email',
                'users.phone as user_phone',
                'users.plate_number',
                'rewards.title as reward_title',
                'rewards.description as reward_description',
                'rewards.image as reward_image',
                'rewards.terms_conditions',
                'administrators.first_name as admin_first_name',
                'administrators.last_name as admin_last_name'
            )
            ->first();
        
        if (!$redemption) {
            return redirect()->route('admin.canjes.index')
                ->with('error', 'Canje no encontrado');
        }
        
        return view('admin.canjes.show', compact('redemption'));
    }
    
    public function aprobar($redemptionId)
    {
        try {
            $adminId = Session::get('admin_id');
            
            DB::table('reward_redemptions')
                ->where('redemption_id', $redemptionId)
                ->where('status', 'pending')
                ->update([
                    'status' => 'approved',
                    'approved_by' => $adminId,
                    'approved_at' => now(),
                    'updated_at' => now()
                ]);
            
            Log::info('Canje aprobado - redemption_id: ' . $redemptionId . ', admin_id: ' . $adminId);
            
            return back()->with('success', 'Canje aprobado correctamente');
            
        } catch (\Exception $e) {
            Log::error('Error al aprobar canje: ' . $e->getMessage());
            return back()->with('error', 'Error al aprobar el canje');
        }
    }
    
    public function entregar($redemptionId)
    {
        try {
            DB::table('reward_redemptions')
                ->where('redemption_id', $redemptionId)
                ->whereIn('status', ['pending', 'approved'])
                ->update([
                    'status' => 'delivered',
                    'delivered_at' => now(),
                    'updated_at' => now()
                ]);
            
            Log::info('Canje entregado - redemption_id: ' . $redemptionId);
            
            return back()->with('success', 'Canje marcado como entregado');
            
        } catch (\Exception $e) {
            Log::error('Error al entregar canje: ' . $e->getMessage());
            return back()->with('error', 'Error al marcar como entregado');
        }
    }
    
    public function cancelar(Request $request, $redemptionId)
    {
        $request->validate([
            'notes' => 'required|string|max:500'
        ]);
        
        try {
            DB::beginTransaction();
            
            $redemption = DB::table('reward_redemptions')
                ->where('redemption_id', $redemptionId)
                ->first();
            
            if (!$redemption) {
                throw new \Exception('Canje no encontrado');
            }
            
            if ($redemption->status == 'cancelled') {
                throw new \Exception('El canje ya está cancelado');
            }
            
            DB::table('reward_redemptions')
                ->where('redemption_id', $redemptionId)
                ->update([
                    'status' => 'cancelled',
                    'notes' => $request->notes,
                    'updated_at' => now()
                ]);
            
            DB::table('users')
                ->where('user_id', $redemption->user_id)
                ->increment('points', $redemption->points_used);
            
            DB::table('point_transactions')->insert([
                'user_id' => $redemption->user_id,
                'type' => 'adjusted',
                'points' => $redemption->points_used,
                'description' => 'Devolución por cancelación de canje: ' . $redemption->redemption_code,
                'reward_id' => $redemption->reward_id,
                'admin_id' => Session::get('admin_id'),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::table('rewards')
                ->where('reward_id', $redemption->reward_id)
                ->increment('stock');
            
            DB::commit();
            
            Log::info('Canje cancelado - redemption_id: ' . $redemptionId);
            
            return back()->with('success', 'Canje cancelado y puntos devueltos al usuario');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al cancelar canje: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }
}