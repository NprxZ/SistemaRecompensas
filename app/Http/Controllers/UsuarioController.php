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
        
        $user = DB::table('users')
            ->where('user_id', $userId)
            ->first();
        
        if (!$user) {
            return redirect()->route('home')->with('error', 'Usuario no encontrado');
        }
        
        Session::put('user_points', $user->points);
        
        $recentTransactions = DB::table('point_transactions')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
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
        
        $featuredRewards = $query->limit(6)->get();
        
        $recentRedemptions = DB::table('reward_redemptions')
            ->join('rewards', 'reward_redemptions.reward_id', '=', 'rewards.reward_id')
            ->where('reward_redemptions.user_id', $userId)
            ->select('reward_redemptions.*', 'rewards.title', 'rewards.image')
            ->orderBy('reward_redemptions.created_at', 'desc')
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
        
        $user = DB::table('users')
            ->where('user_id', $userId)
            ->first();
        
        if (!$user) {
            return redirect()->route('home')->with('error', 'Usuario no encontrado');
        }
        
        return view('usuario.perfil', compact('user'));
    }
    

    public function updatePerfil(Request $request)
    {
        $userId = Session::get('user_id');
        
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
        
        $user = DB::table('users')
            ->where('user_id', $userId)
            ->first();
        
        Session::put('user_points', $user->points);
        
        $pointsSummary = [
            'earned' => DB::table('point_transactions')
                ->where('user_id', $userId)
                ->where('type', 'earned')
                ->sum('points'),
            'redeemed' => abs(DB::table('point_transactions')
                ->where('user_id', $userId)
                ->where('type', 'redeemed')
                ->sum('points')),
            'expired' => abs(DB::table('point_transactions')
                ->where('user_id', $userId)
                ->where('type', 'expired')
                ->sum('points'))
        ];
        
        return view('usuario.puntos', compact('user', 'pointsSummary'));
    }
    

    public function historial(Request $request)
    {
        $userId = Session::get('user_id');
        
        $query = DB::table('point_transactions')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc');
        
        if ($request->has('type') && $request->type != 'all') {
            $query->where('type', $request->type);
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
            $user = DB::table('users')->where('user_id', $userId)->first();
            
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'La contraseña actual es incorrecta');
            }
            
            DB::table('users')
                ->where('user_id', $userId)
                ->update([
                    'password' => Hash::make($request->new_password),
                    'updated_at' => now()
                ]);
            
            Log::info('Contraseña cambiada para user_id: ' . $userId);
            
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

    $redemption = DB::table('reward_redemptions as r')
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

    $builder = new Builder(
        writer: new SvgWriter(),
        data: $redemption->redemption_code,
        size: 200,
        margin: 10
    );
    
    $result = $builder->build();
    $qrCodeBase64 = base64_encode($result->getString());

    $user = DB::table('users')->where('user_id', $userId)->first();

    $pdf = PDF::loadView('pdf.redemption-voucher', [
        'redemption' => $redemption,
        'user' => $user,
        'qrCode' => $qrCodeBase64
    ]);

    return $pdf->download('canje-' . $redemption->redemption_code . '.pdf');
}


public function sendRedemptionEmail($redemption_id)
{
    $userId = Session::get('user_id');
    
    if (!$userId) {
        return redirect()->route('usuario.login');
    }

    $redemption = DB::table('reward_redemptions as r')
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

    $builder = new Builder(
        writer: new SvgWriter(),
        data: $redemption->redemption_code,
        size: 200,
        margin: 10
    );
    
    $result = $builder->build();
    $qrCodeBase64 = base64_encode($result->getString());

    $user = DB::table('users')->where('user_id', $userId)->first();

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