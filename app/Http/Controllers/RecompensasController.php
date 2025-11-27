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
        
        $user = DB::table('usuarios')
            ->where('usuario_id', $userId)
            ->first();
        
        if (!$user) {
            return redirect()->route('home')->with('error', 'Usuario no encontrado');
        }
        
        $query = DB::table('recompensas')
            ->where('activo', 1);
        
        if ($request->has('category') && $request->category != '') {
            $query->where('categoria', $request->category);
        }
        
        if ($request->has('available')) {
            if ($request->available == 'yes') {
                $query->where('inventario', '>', 0);
            } elseif ($request->available == 'affordable') {
                $query->where('inventario', '>', 0)
                      ->where('puntos_requeridos', '<=', $user->puntos);
            }
        }
        
        $order = $request->get('order', 'points_asc');
        switch ($order) {
            case 'points_desc':
                $query->orderBy('puntos_requeridos', 'desc');
                break;
            case 'newest':
                $query->orderBy('fecha_creacion', 'desc');
                break;
            case 'points_asc':
            default:
                $query->orderBy('puntos_requeridos', 'asc');
                break;
        }
        
        $rewards = $query->paginate(12);
        
        return view('usuario.recompensas.catalogo', compact('user', 'rewards'));
    }
    
    public function detalle($recompensa_id)
    {
        $userId = Session::get('user_id');
        
        $user = DB::table('usuarios')
            ->where('usuario_id', $userId)
            ->first();
        
        if (!$user) {
            return redirect()->route('home')->with('error', 'Usuario no encontrado');
        }
        
        $reward = DB::table('recompensas')
            ->where('recompensa_id', $recompensa_id)
            ->where('activo', 1)
            ->first();
        
        if (!$reward) {
            return redirect()->route('usuario.recompensas.catalogo')
                ->with('error', 'Recompensa no encontrada');
        }
        
        return view('usuario.recompensas.detalle', compact('user', 'reward'));
    }
    
    public function canjear(Request $request, $recompensa_id)
    {
        $userId = Session::get('user_id');
        
        DB::beginTransaction();
        
        try {
            $user = DB::table('usuarios')
                ->where('usuario_id', $userId)
                ->lockForUpdate() 
                ->first();
            
            if (!$user) {
                DB::rollBack();
                return redirect()->route('home')->with('error', 'Usuario no encontrado');
            }
            
            $reward = DB::table('recompensas')
                ->where('recompensa_id', $recompensa_id)
                ->lockForUpdate()
                ->first();
            
            if (!$reward) {
                DB::rollBack();
                return back()->with('error', 'Recompensa no encontrada');
            }
            
            if (!$reward->activo) {
                DB::rollBack();
                return back()->with('error', 'Esta recompensa no está activa');
            }
            
            if ($reward->inventario <= 0) {
                DB::rollBack();
                return back()->with('error', 'Esta recompensa está agotada');
            }
            
            if ($user->puntos < $reward->puntos_requeridos) {
                DB::rollBack();
                return back()->with('error', 'No tienes suficientes puntos para canjear esta recompensa');
            }
            
            if ($reward->fecha_expiracion && $reward->fecha_expiracion < now()) {
                DB::rollBack();
                return back()->with('error', 'Esta recompensa ha expirado');
            }
            
            $redemptionCode = $this->generateRedemptionCode();
            
            $redemptionId = DB::table('canjes_recompensas')->insertGetId([
                'usuario_id' => $userId,
                'recompensa_id' => $recompensa_id,
                'puntos_utilizados' => $reward->puntos_requeridos,
                'estado' => 'pendiente',
                'codigo_canje' => $redemptionCode,
                'notas' => null,
                'fecha_creacion' => now(),
                'fecha_actualizacion' => now()
            ]);
            
            DB::table('usuarios')
                ->where('usuario_id', $userId)
                ->update([
                    'puntos' => $user->puntos - $reward->puntos_requeridos,
                    'fecha_actualizacion' => now()
                ]);
            
            Session::put('user_points', $user->puntos - $reward->puntos_requeridos);
            
            DB::table('recompensas')
                ->where('recompensa_id', $recompensa_id)
                ->update([
                    'inventario' => $reward->inventario - 1,
                    'fecha_actualizacion' => now()
                ]);
            
            DB::table('transacciones_puntos')->insert([
                'usuario_id' => $userId,
                'tipo' => 'canjeado',
                'puntos' => -$reward->puntos_requeridos,
                'descripcion' => 'Canje de recompensa: ' . $reward->titulo,
                'recompensa_id' => $recompensa_id,
                'fecha_creacion' => now(),
                'fecha_actualizacion' => now()
            ]);
            
            DB::commit();
            
            Log::info('Canje exitoso', [
                'usuario_id' => $userId,
                'recompensa_id' => $recompensa_id,
                'canje_id' => $redemptionId,
                'codigo_canje' => $redemptionCode,
                'puntos_utilizados' => $reward->puntos_requeridos
            ]);
            
            return redirect()->route('usuario.mis-canjes.detalle', $redemptionId)
                ->with('success', '¡Canje realizado exitosamente! Tu código de canje es: ' . $redemptionCode);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al procesar canje: ' . $e->getMessage(), [
                'usuario_id' => $userId,
                'recompensa_id' => $recompensa_id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Error al procesar el canje. Por favor, intenta nuevamente.');
        }
    }
    
    public function misCanjes(Request $request)
    {
        $userId = Session::get('user_id');
        
        $query = DB::table('canjes_recompensas')
            ->join('recompensas', 'canjes_recompensas.recompensa_id', '=', 'recompensas.recompensa_id')
            ->where('canjes_recompensas.usuario_id', $userId)
            ->select(
                'canjes_recompensas.*',
                'recompensas.titulo',
                'recompensas.descripcion',
                'recompensas.imagen',
                'recompensas.categoria'
            )
            ->orderBy('canjes_recompensas.fecha_creacion', 'desc');
        
        if ($request->has('status') && $request->status != '') {
            $query->where('canjes_recompensas.estado', $request->status);
        }
        
        $redemptions = $query->paginate(10);
        
        return view('usuario.mis-canjes', compact('redemptions'));
    }
    
    public function detalleCanjes($canje_id)
    {
        $userId = Session::get('user_id');
        
        $redemption = DB::table('canjes_recompensas')
            ->join('recompensas', 'canjes_recompensas.recompensa_id', '=', 'recompensas.recompensa_id')
            ->where('canjes_recompensas.canje_id', $canje_id)
            ->where('canjes_recompensas.usuario_id', $userId)
            ->select(
                'canjes_recompensas.*',
                'recompensas.titulo',
                'recompensas.descripcion',
                'recompensas.imagen',
                'recompensas.categoria',
                'recompensas.terminos_condiciones'
            )
            ->first();
        
        if (!$redemption) {
            return redirect()->route('usuario.mis-canjes')
                ->with('error', 'Canje no encontrado');
        }
        
        $approvedBy = null;
        if ($redemption->aprobado_por) {
            $approvedBy = DB::table('administradores')
                ->where('administrador_id', $redemption->aprobado_por)
                ->first();
        }
        
        return view('usuario.mis-canjes-detalle', compact('redemption', 'approvedBy'));
    }
    

    private function generateRedemptionCode()
    {
        do {
            $code = strtoupper(Str::random(10));
            
            $exists = DB::table('canjes_recompensas')
                ->where('codigo_canje', $code)
                ->exists();
                
        } while ($exists);
        
        return $code;
    }
    
    public function downloadRedemptionPDF($canje_id)
    {
        $userId = Session::get('user_id');
        
        if (!$userId) {
            return redirect()->route('usuario.login');
        }

        $redemption = DB::table('canjes_recompensas as cr')
            ->join('recompensas as r', 'cr.recompensa_id', '=', 'r.recompensa_id')
            ->where('cr.canje_id', $canje_id)
            ->where('cr.usuario_id', $userId)
            ->select(
                'cr.*',
                'r.titulo',
                'r.descripcion',
                'r.imagen',
                'r.categoria',
                'r.terminos_condiciones'
            )
            ->first();

        if (!$redemption) {
            return redirect()->route('usuario.mis-canjes')
                ->withErrors(['error' => 'Canje no encontrado']);
        }

        $result = Builder::create()
            ->writer(new SvgWriter())
            ->data($redemption->codigo_canje)
            ->size(200)
            ->margin(10)
            ->build();

        $qrCode = base64_encode($result->getString());

        $user = DB::table('usuarios')->where('usuario_id', $userId)->first();

        $pdf = PDF::loadView('pdf.redemption-voucher', [
            'redemption' => $redemption,
            'user' => $user,
            'qrCode' => $qrCode
        ]);

        return $pdf->download('canje-' . $redemption->codigo_canje . '.pdf');
    }
}