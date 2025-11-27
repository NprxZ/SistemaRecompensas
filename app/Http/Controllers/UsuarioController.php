<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\SvgWriter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\RedemptionVoucher;

class UsuarioController extends Controller
{

    public function dashboard(Request $request)
    {
        $userId = Session::get('user_id');
        
        // Tabla: users → usuarios
        // Columnas: user_id → usuario_id, points → puntos
        $user = DB::table('usuarios')
            ->where('usuario_id', $userId)
            ->first();
        
        if (!$user) {
            return redirect()->route('home')->with('error', 'Usuario no encontrado');
        }
        
        Session::put('user_points', $user->puntos);
        
        // Tabla: point_transactions → transacciones_puntos
        // Columnas: user_id → usuario_id, created_at → fecha_creacion
        $recentTransactions = DB::table('transacciones_puntos')
            ->where('usuario_id', $userId)
            ->orderBy('fecha_creacion', 'desc')
            ->limit(5)
            ->get();
        
        // Tabla: rewards → recompensas
        // Columnas: active → activo, category → categoria, stock → inventario,
        // points_required → puntos_requeridos, created_at → fecha_creacion
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
        
        $featuredRewards = $query->limit(6)->get();
        
        // Tabla: reward_redemptions → canjes_recompensas
        // Columnas: reward_id → recompensa_id, user_id → usuario_id,
        // title → titulo, image → imagen, created_at → fecha_creacion
        $recentRedemptions = DB::table('canjes_recompensas')
            ->join('recompensas', 'canjes_recompensas.recompensa_id', '=', 'recompensas.recompensa_id')
            ->where('canjes_recompensas.usuario_id', $userId)
            ->select('canjes_recompensas.*', 'recompensas.titulo', 'recompensas.imagen')
            ->orderBy('canjes_recompensas.fecha_creacion', 'desc')
            ->limit(3)
            ->get();
        
        return view('usuario.dashboard', compact(
            'user',
            'recentTransactions',
            'featuredRewards',
            'recentRedemptions'
        ));
    }
    

    public function perfil()
    {
        $userId = Session::get('user_id');
        
        // Tabla: users → usuarios
        // Columnas: user_id → usuario_id
        $user = DB::table('usuarios')
            ->where('usuario_id', $userId)
            ->first();
        
        if (!$user) {
            return redirect()->route('home')->with('error', 'Usuario no encontrado');
        }
        
        return view('usuario.perfil', compact('user'));
    }
    

    public function updatePerfil(Request $request)
    {
        $userId = Session::get('user_id');
        
        // Validación con nombres en español
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:100|unique:usuarios,email,' . $userId . ',usuario_id',
            'phone' => 'nullable|string|max:20',
            'vehicle_brand' => 'required|string|max:50',
            'vehicle_model' => 'required|string|max:50',
            'vehicle_year' => 'required|integer|min:1900|max:' . (date('Y') + 1)
        ]);
        
        try {
            // Tabla: users → usuarios
            // Columnas: user_id → usuario_id, first_name → nombre, last_name → apellido,
            // phone → telefono, vehicle_brand → marca_vehiculo, vehicle_model → modelo_vehiculo,
            // vehicle_year → anio_vehiculo, updated_at → fecha_actualizacion
            DB::table('usuarios')
                ->where('usuario_id', $userId)
                ->update([
                    'nombre' => $request->first_name,
                    'apellido' => $request->last_name,
                    'email' => $request->email,
                    'telefono' => $request->phone,
                    'marca_vehiculo' => $request->vehicle_brand,
                    'modelo_vehiculo' => $request->vehicle_model,
                    'anio_vehiculo' => $request->vehicle_year,
                    'fecha_actualizacion' => now()
                ]);
            
            Session::put('user_name', $request->first_name . ' ' . $request->last_name);
            Session::put('user_email', $request->email);
            Session::put('user_vehicle', $request->vehicle_brand . ' ' . $request->vehicle_model);
            
            return back()->with('success', 'Perfil actualizado correctamente');
            
        } catch (\Exception $e) {
            Log::error('Error al actualizar perfil: ' . $e->getMessage());
            return back()->with('error', 'Error al actualizar el perfil');
        }
    }
    

    public function puntos()
    {
        $userId = Session::get('user_id');
        
        // Tabla: users → usuarios
        // Columnas: user_id → usuario_id, points → puntos
        $user = DB::table('usuarios')
            ->where('usuario_id', $userId)
            ->first();
        
        Session::put('user_points', $user->puntos);
        
        // Tabla: point_transactions → transacciones_puntos
        // Columnas: user_id → usuario_id, type → tipo, points → puntos
        $pointsSummary = [
            'earned' => DB::table('transacciones_puntos')
                ->where('usuario_id', $userId)
                ->where('tipo', 'ganado')
                ->sum('puntos'),
            'redeemed' => abs(DB::table('transacciones_puntos')
                ->where('usuario_id', $userId)
                ->where('tipo', 'canjeado')
                ->sum('puntos')),
            'expired' => abs(DB::table('transacciones_puntos')
                ->where('usuario_id', $userId)
                ->where('tipo', 'expirado')
                ->sum('puntos'))
        ];
        
        return view('usuario.puntos', compact('user', 'pointsSummary'));
    }
    

    public function historial(Request $request)
    {
        $userId = Session::get('user_id');
        
        // Tabla: point_transactions → transacciones_puntos
        // Columnas: user_id → usuario_id, created_at → fecha_creacion, type → tipo
        $query = DB::table('transacciones_puntos')
            ->where('usuario_id', $userId)
            ->orderBy('fecha_creacion', 'desc');
        
        if ($request->has('type') && $request->type != 'all') {
            $query->where('tipo', $request->type);
        }
        
        $transactions = $query->paginate(20);
        
        return view('usuario.historial', compact('transactions'));
    }
    

    public function changePassword(Request $request)
    {
        $userId = Session::get('user_id');
        
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed'
        ], [
            'current_password.required' => 'La contraseña actual es obligatoria',
            'new_password.required' => 'La nueva contraseña es obligatoria',
            'new_password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'new_password.confirmed' => 'Las contraseñas no coinciden'
        ]);
        
        try {
            // Tabla: users → usuarios
            // Columnas: user_id → usuario_id
            $user = DB::table('usuarios')->where('usuario_id', $userId)->first();
            
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'La contraseña actual es incorrecta');
            }
            
            // Columnas: updated_at → fecha_actualizacion
            DB::table('usuarios')
                ->where('usuario_id', $userId)
                ->update([
                    'password' => Hash::make($request->new_password),
                    'fecha_actualizacion' => now()
                ]);
            
            Log::info('Contraseña cambiada para usuario_id: ' . $userId);
            
            return back()->with('success', 'Contraseña actualizada correctamente');
            
        } catch (\Exception $e) {
            Log::error('Error al cambiar contraseña: ' . $e->getMessage());
            return back()->with('error', 'Error al cambiar la contraseña');
        }
    }


    public function downloadRedemptionPDF($redemption_id)
    {
        $userId = Session::get('user_id');
        
        if (!$userId) {
            return redirect()->route('usuario.login');
        }

        // Tablas: reward_redemptions → canjes_recompensas, rewards → recompensas
        // Columnas: redemption_id → canje_id, reward_id → recompensa_id, user_id → usuario_id,
        // title → titulo, description → descripcion, image → imagen, category → categoria,
        // terms_conditions → terminos_condiciones, redemption_code → codigo_canje
        $redemption = DB::table('canjes_recompensas as cr')
            ->join('recompensas as r', 'cr.recompensa_id', '=', 'r.recompensa_id')
            ->where('cr.canje_id', $redemption_id)
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

        $builder = new Builder(
            writer: new SvgWriter(),
            data: $redemption->codigo_canje,
            size: 200,
            margin: 10
        );
        
        $result = $builder->build();
        $qrCodeBase64 = base64_encode($result->getString());

        // Tabla: users → usuarios
        // Columnas: user_id → usuario_id
        $user = DB::table('usuarios')->where('usuario_id', $userId)->first();

        $pdf = PDF::loadView('pdf.redemption-voucher', [
            'redemption' => $redemption,
            'user' => $user,
            'qrCode' => $qrCodeBase64
        ]);

        return $pdf->download('canje-' . $redemption->codigo_canje . '.pdf');
    }




    public function sendRedemptionEmail($redemption_id)
{


    $userId = Session::get('user_id');
        
        if (!$userId) {
            return redirect()->route('usuario.login');
        }

        $redemption = DB::table('canjes_recompensas as cr')
            ->join('recompensas as r', 'cr.recompensa_id', '=', 'r.recompensa_id')
            ->where('cr.canje_id', $redemption_id)
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

    $builder = new Builder(
        writer: new SvgWriter(),
        data: $redemption->codigo_canje,
        size: 200,
        margin: 10
    );

    
    $result = $builder->build();
    $qrCodeBase64 = base64_encode($result->getString());

  
     $user = DB::table('usuarios')->where('usuario_id', $userId)->first();

    $pdf = PDF::loadView('pdf.redemption-voucher', [
        'redemption' => $redemption,
        'user' => $user,
        'qrCode' => $qrCodeBase64
    ]);

    $pdfContent = $pdf->output();

    try {
        Mail::to($user->email)->send(new RedemptionVoucher($redemption, $user, $pdfContent));
        
        return back()->with('success', '¡Comprobante enviado a tu correo: ' . $user->email . '!');
    } catch (\Exception $e) {
        Log::error('Error al enviar correo: ' . $e->getMessage());
        return back()->withErrors(['error' => 'No se pudo enviar el correo. Intenta nuevamente.']);
    }
}
}