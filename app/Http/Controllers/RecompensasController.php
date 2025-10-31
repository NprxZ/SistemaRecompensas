<?php

namespace App\Http\Controllers;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\SvgWriter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RecompensasController extends Controller
{

    public function catalogo(Request $request)
    {
        $userId = Session::get('user_id');
        
        $user = DB::table('users')
            ->where('user_id', $userId)
            ->first();
        
        if (!$user) {
            return redirect()->route('home')->with('error', 'Usuario no encontrado');
        }
        
        $query = DB::table('rewards')
            ->where('active', 1);
        
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }
        
        if ($request->has('available')) {
            if ($request->available == 'yes') {
                $query->where('stock', '>', 0);
            } elseif ($request->available == 'affordable') {
                $query->where('stock', '>', 0)
                      ->where('points_required', '<=', $user->points);
            }
        }
        
        $order = $request->get('order', 'points_asc');
        switch ($order) {
            case 'points_desc':
                $query->orderBy('points_required', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'points_asc':
            default:
                $query->orderBy('points_required', 'asc');
                break;
        }
        
        $rewards = $query->paginate(12);
        
        return view('usuario.recompensas.catalogo', compact('user', 'rewards'));
    }
    
    public function detalle($reward_id)
    {
        $userId = Session::get('user_id');
        
        $user = DB::table('users')
            ->where('user_id', $userId)
            ->first();
        
        if (!$user) {
            return redirect()->route('home')->with('error', 'Usuario no encontrado');
        }
        
        $reward = DB::table('rewards')
            ->where('reward_id', $reward_id)
            ->where('active', 1)
            ->first();
        
        if (!$reward) {
            return redirect()->route('usuario.recompensas.catalogo')
                ->with('error', 'Recompensa no encontrada');
        }
        
        return view('usuario.recompensas.detalle', compact('user', 'reward'));
    }
    
    public function canjear(Request $request, $reward_id)
    {
        $userId = Session::get('user_id');
        
        DB::beginTransaction();
        
        try {
            $user = DB::table('users')
                ->where('user_id', $userId)
                ->lockForUpdate() 
                ->first();
            
            if (!$user) {
                DB::rollBack();
                return redirect()->route('home')->with('error', 'Usuario no encontrado');
            }
            
            $reward = DB::table('rewards')
                ->where('reward_id', $reward_id)
                ->lockForUpdate()
                ->first();
            
            if (!$reward) {
                DB::rollBack();
                return back()->with('error', 'Recompensa no encontrada');
            }
            
            if (!$reward->active) {
                DB::rollBack();
                return back()->with('error', 'Esta recompensa no está activa');
            }
            
            if ($reward->stock <= 0) {
                DB::rollBack();
                return back()->with('error', 'Esta recompensa está agotada');
            }
            
            if ($user->points < $reward->points_required) {
                DB::rollBack();
                return back()->with('error', 'No tienes suficientes puntos para canjear esta recompensa');
            }
            
            if ($reward->expiration_date && $reward->expiration_date < now()) {
                DB::rollBack();
                return back()->with('error', 'Esta recompensa ha expirado');
            }
            
            $redemptionCode = $this->generateRedemptionCode();
            
            $redemptionId = DB::table('reward_redemptions')->insertGetId([
                'user_id' => $userId,
                'reward_id' => $reward_id,
                'points_used' => $reward->points_required,
                'status' => 'pending',
                'redemption_code' => $redemptionCode,
                'notes' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::table('users')
                ->where('user_id', $userId)
                ->update([
                    'points' => $user->points - $reward->points_required,
                    'updated_at' => now()
                ]);
            
            Session::put('user_points', $user->points - $reward->points_required);
            
            DB::table('rewards')
                ->where('reward_id', $reward_id)
                ->update([
                    'stock' => $reward->stock - 1,
                    'updated_at' => now()
                ]);
            
            DB::table('point_transactions')->insert([
                'user_id' => $userId,
                'type' => 'redeemed',
                'points' => -$reward->points_required,
                'description' => 'Canje de recompensa: ' . $reward->title,
                'reward_id' => $reward_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::commit();
            
            Log::info('Canje exitoso', [
                'user_id' => $userId,
                'reward_id' => $reward_id,
                'redemption_id' => $redemptionId,
                'redemption_code' => $redemptionCode,
                'points_used' => $reward->points_required
            ]);
            
            return redirect()->route('usuario.mis-canjes.detalle', $redemptionId)
                ->with('success', '¡Canje realizado exitosamente! Tu código de canje es: ' . $redemptionCode);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al procesar canje: ' . $e->getMessage(), [
                'user_id' => $userId,
                'reward_id' => $reward_id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Error al procesar el canje. Por favor, intenta nuevamente.');
        }
    }
    
    public function misCanjes(Request $request)
    {
        $userId = Session::get('user_id');
        
        $query = DB::table('reward_redemptions')
            ->join('rewards', 'reward_redemptions.reward_id', '=', 'rewards.reward_id')
            ->where('reward_redemptions.user_id', $userId)
            ->select(
                'reward_redemptions.*',
                'rewards.title',
                'rewards.description',
                'rewards.image',
                'rewards.category'
            )
            ->orderBy('reward_redemptions.created_at', 'desc');
        
        if ($request->has('status') && $request->status != '') {
            $query->where('reward_redemptions.status', $request->status);
        }
        
        $redemptions = $query->paginate(10);
        
        return view('usuario.mis-canjes', compact('redemptions'));
    }
    
    public function detalleCanjes($redemption_id)
    {
        $userId = Session::get('user_id');
        
        $redemption = DB::table('reward_redemptions')
            ->join('rewards', 'reward_redemptions.reward_id', '=', 'rewards.reward_id')
            ->where('reward_redemptions.redemption_id', $redemption_id)
            ->where('reward_redemptions.user_id', $userId)
            ->select(
                'reward_redemptions.*',
                'rewards.title',
                'rewards.description',
                'rewards.image',
                'rewards.category',
                'rewards.terms_conditions'
            )
            ->first();
        
        if (!$redemption) {
            return redirect()->route('usuario.mis-canjes')
                ->with('error', 'Canje no encontrado');
        }
        
        $approvedBy = null;
        if ($redemption->approved_by) {
            $approvedBy = DB::table('administrators')
                ->where('admin_id', $redemption->approved_by)
                ->first();
        }
        
        return view('usuario.mis-canjes-detalle', compact('redemption', 'approvedBy'));
    }
    

    private function generateRedemptionCode()
    {
        do {
            $code = strtoupper(Str::random(10));
            
            $exists = DB::table('reward_redemptions')
                ->where('redemption_code', $code)
                ->exists();
                
        } while ($exists);
        
        return $code;
    }
    public function downloadRedemptionPDF($redemption_id)
{
    $userId = Session::get('user_id');
    
    if (!$userId) {
        return redirect()->route('usuario.login');
    }

    $redemption = DB::table('redemptions as r')
        ->join('rewards as rw', 'r.reward_id', '=', 'rw.reward_id')
        ->where('r.redemption_id', $redemption_id)
        ->where('r.user_id', $userId)
        ->select(
            'r.*',
            'rw.title',
            'rw.description',
            'rw.image',
            'rw.category',
            'rw.terms_conditions'
        )
        ->first();

    if (!$redemption) {
        return redirect()->route('usuario.mis-canjes')
            ->withErrors(['error' => 'Canje no encontrado']);
    }

    $result = Builder::create()
        ->writer(new SvgWriter())
        ->data($redemption->redemption_code)
        ->size(200)
        ->margin(10)
        ->build();

    $qrCode = base64_encode($result->getString());

    $user = DB::table('users')->where('user_id', $userId)->first();

    $pdf = PDF::loadView('pdf.redemption-voucher', [
        'redemption' => $redemption,
        'user' => $user,
        'qrCode' => $qrCode
    ]);

    return $pdf->download('canje-' . $redemption->redemption_code . '.pdf');
}
}