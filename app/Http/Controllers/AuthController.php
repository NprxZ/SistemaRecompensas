<?php

namespace App\Http\Controllers;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\SvgWriter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Debe ser un correo electrónico válido',
            'password.required' => 'La contraseña es obligatoria'
        ]);

        $admin = DB::table('administrators')
            ->where('email', $request->email)
            ->where('active', 1)
            ->first();

        if (!$admin) {
            return back()->withErrors([
                'email' => 'Las credenciales no coinciden con nuestros registros.'
            ])->withInput($request->only('email'));
        }

        if ($request->password !== $admin->password) {
            return back()->withErrors([
                'email' => 'Las credenciales no coinciden con nuestros registros.'
            ])->withInput($request->only('email'));
        }

        try {
            DB::table('administrators')
                ->where('admin_id', $admin->admin_id)
                ->update(['last_login' => now(), 'updated_at' => now()]);

            Log::info('Login exitoso para admin_id: ' . $admin->admin_id);

        } catch (\Exception $e) {
            Log::error('Error al actualizar last_login: ' . $e->getMessage());
        }

        Session::put('admin_id', $admin->admin_id);
        Session::put('admin_name', $admin->first_name . ' ' . $admin->last_name);
        Session::put('admin_email', $admin->email);
        Session::put('user_role', $admin->role);

        return redirect()->route('admin.dashboard')->with('success', '¡Bienvenido ' . $admin->first_name . '!');
    }

    public function logout(Request $request)
    {
        $adminId = Session::get('admin_id');

        if ($adminId) {
            Log::info('Logout para admin_id: ' . $adminId);
        }

        Session::flush();
        return redirect()->route('home')->with('success', 'Sesión cerrada correctamente');
    }

    public function check()
    {
        if (Session::has('admin_id')) {
            return response()->json([
                'authenticated' => true,
                'user' => [
                    'name' => Session::get('admin_name'),
                    'email' => Session::get('admin_email'),
                    'role' => Session::get('user_role')
                ]
            ]);
        }

        if (Session::has('user_id')) {
            return response()->json([
                'authenticated' => true,
                'user' => [
                    'name' => Session::get('user_name'),
                    'email' => Session::get('user_email'),
                    'plate_number' => Session::get('user_plate'),
                    'points' => Session::get('user_points'),
                    'role' => 'usuario'
                ]
            ]);
        }

        return response()->json(['authenticated' => false]);
    }

    public function loginUsuario(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string',
            'password' => 'required'
        ], [
            'plate_number.required' => 'La placa del vehículo es obligatoria',
            'password.required' => 'La contraseña es obligatoria'
        ]);

        $user = DB::table('users')
            ->where('plate_number', strtoupper(trim($request->plate_number)))
            ->where('active', 1)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'plate_number' => 'Las credenciales no coinciden con nuestros registros.'
            ])->withInput($request->only('plate_number'));
        }

        if ($request->password !== $user->password) {
            return back()->withErrors([
                'plate_number' => 'Las credenciales no coinciden con nuestros registros.'
            ])->withInput($request->only('plate_number'));
        }

        try {
            DB::table('users')
                ->where('user_id', $user->user_id)
                ->update(['last_login' => now(), 'updated_at' => now()]);

            Log::info('Login exitoso para user_id: ' . $user->user_id);

        } catch (\Exception $e) {
            Log::error('Error al actualizar last_login: ' . $e->getMessage());
        }

        Session::put('user_id', $user->user_id);
        Session::put('user_name', $user->first_name . ' ' . $user->last_name);
        Session::put('user_email', $user->email);
        Session::put('user_plate', $user->plate_number);
        Session::put('user_points', $user->points);
        Session::put('user_vehicle', $user->vehicle_brand . ' ' . $user->vehicle_model);
        Session::put('user_role', 'usuario');

        return redirect()->route('usuario.dashboard')
            ->with('success', '¡Bienvenido ' . $user->first_name . '!');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function registerUsuario(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:100|unique:users,email',
            'plate_number' => 'required|string|max:20|unique:users,plate_number',
            'phone' => 'nullable|string|max:20',
            'vehicle_brand' => 'required|string|max:50',
            'vehicle_model' => 'required|string|max:50',
            'vehicle_year' => 'required|integer|min:1900|max:' . (date('Y') + 1)
        ], [
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'plate_number.unique' => 'Esta placa ya está registrada.',
            'first_name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'plate_number.required' => 'La placa del vehículo es obligatoria.',
            'vehicle_brand.required' => 'La marca del vehículo es obligatoria.',
            'vehicle_model.required' => 'El modelo del vehículo es obligatorio.',
            'vehicle_year.required' => 'El año del vehículo es obligatorio.'
        ]);

        try {
            $tempPassword = Str::random(8);
            
            $userId = DB::table('users')->insertGetId([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'plate_number' => strtoupper(trim($request->plate_number)),
                'password' => $tempPassword, 
                'phone' => $request->phone,
                'vehicle_brand' => $request->vehicle_brand,
                'vehicle_model' => $request->vehicle_model,
                'vehicle_year' => $request->vehicle_year,
                'points' => 100,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

   
            DB::table('point_transactions')->insert([
                'user_id' => $userId,
                'type' => 'earned',
                'points' => 100,
                'description' => 'Puntos de bienvenida por registro',
                'created_at' => now(),
                'updated_at' => now()
            ]);

   
            try {
                Mail::send('emails.welcome-user', [
                    'userName' => $request->first_name,
                    'plateNumber' => strtoupper(trim($request->plate_number)),
                    'tempPassword' => $tempPassword,
                    'email' => $request->email
                ], function($message) use ($request) {
                    $message->to($request->email)
                            ->subject('¡Bienvenido al Sistema de Recompensas!');
                });

                Log::info('Correo de bienvenida enviado a: ' . $request->email);
            } catch (\Exception $e) {
                Log::error('Error al enviar correo de bienvenida: ' . $e->getMessage());
            }

         
            $user = DB::table('users')->where('user_id', $userId)->first();
            
            Session::put('user_id', $user->user_id);
            Session::put('user_name', $user->first_name . ' ' . $user->last_name);
            Session::put('user_email', $user->email);
            Session::put('user_plate', $user->plate_number);
            Session::put('user_points', $user->points);
            Session::put('user_vehicle', $user->vehicle_brand . ' ' . $user->vehicle_model);
            Session::put('user_role', 'usuario');

            return redirect()->route('usuario.dashboard')
                ->with('success', '¡Registro exitoso! Te hemos enviado tu contraseña temporal por correo.');

        } catch (\Exception $e) {
            Log::error('Error al registrar usuario: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Hubo un error al registrar tu cuenta. Por favor intenta de nuevo.'
            ])->withInput();
        }
    }


    public function logoutUsuario()
    {
        $userId = Session::get('user_id');
        
        if ($userId) {
            Log::info('Logout para user_id: ' . $userId);
        }

        Session::flush();
        return redirect()->route('home')->with('success', 'Sesión cerrada correctamente');
    }


    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }


    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'plate_number' => 'required|string'
        ]);

        $user = DB::table('users')
            ->where('email', $request->email)
            ->where('plate_number', strtoupper(trim($request->plate_number)))
            ->where('active', 1)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'No encontramos un usuario con esos datos.'
            ]);
        }

        try {
            $tempPassword = Str::random(10);
            
            DB::table('users')
                ->where('user_id', $user->user_id)
                ->update([
                    'password' => $tempPassword,
                    'updated_at' => now()
                ]);

            Mail::send('emails.password-reset-user', [
                'userName' => $user->first_name,
                'plateNumber' => $user->plate_number,
                'tempPassword' => $tempPassword
            ], function($message) use ($user) {
                $message->to($user->email)
                        ->subject('Recuperación de Contraseña - Sistema de Recompensas');
            });

            Log::info('Contraseña restablecida para user_id: ' . $user->user_id);

            return back()->with('success', 'Te hemos enviado una nueva contraseña temporal por correo.');

        } catch (\Exception $e) {
            Log::error('Error al restablecer contraseña: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Hubo un error al procesar tu solicitud. Intenta nuevamente.'
            ]);
        }
    }

    public function sanitizeXSS($input)
    {
        try {
            if (is_array($input)) {
                return array_map([$this, 'sanitizeXSS'], $input);
            }

            $sanitized = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
            $sanitized = strip_tags($sanitized);
            $sanitized = trim($sanitized);

            return $sanitized;

        } catch (\Exception $e) {
            Log::error('Error al sanitizar XSS: ' . $e->getMessage());
            return '';
        }
    }

    public function sanitizeSQL($input, $type = 'string')
    {
        try {
            $input = trim($input);

            switch ($type) {
                case 'email':
                    $sanitized = filter_var($input, FILTER_SANITIZE_EMAIL);
                    if (!filter_var($sanitized, FILTER_VALIDATE_EMAIL)) {
                        throw new \Exception('Email inválido');
                    }
                    break;

                case 'numeric':
                case 'integer':
                    if (!is_numeric($input)) {
                        throw new \Exception('Valor numérico inválido');
                    }
                    $sanitized = $type === 'integer' ? intval($input) : floatval($input);
                    break;

                case 'string':
                default:
                    $sanitized = addslashes($input);
                    $sanitized = preg_replace('/[^\p{L}\p{N}\s\-\_\.\@]/u', '', $sanitized);
                    break;
            }

            return $sanitized;

        } catch (\Exception $e) {
            Log::error('Error al validar SQL: ' . $e->getMessage());
            return null;
        }
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