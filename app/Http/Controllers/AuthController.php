<?php

//CONTROLADOR PRINCIPAL DE AUTENTICACIÓN Y DE LÓGICA DEL SISTEMA

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

        // Tabla: administrators → administradores
        // Columnas: admin_id → administrador_id, active → activo
        $admin = DB::table('administradores')
            ->where('email', $request->email)
            ->where('activo', 1)
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
            // Columnas: last_login → ultimo_acceso, updated_at → fecha_actualizacion
            DB::table('administradores')
                ->where('administrador_id', $admin->administrador_id)
                ->update(['ultimo_acceso' => now(), 'fecha_actualizacion' => now()]);

            Log::info('Login exitoso para administrador_id: ' . $admin->administrador_id);

        } catch (\Exception $e) {
            Log::error('Error al actualizar ultimo_acceso: ' . $e->getMessage());
        }

        // Columnas: admin_id → administrador_id, first_name → nombre, last_name → apellido, role → rol
        Session::put('admin_id', $admin->administrador_id);
        Session::put('admin_name', $admin->nombre . ' ' . $admin->apellido);
        Session::put('admin_email', $admin->email);
        Session::put('user_role', $admin->rol);

        return redirect()->route('admin.dashboard')->with('success', '¡Bienvenido ' . $admin->nombre . '!');
    }

    public function logout(Request $request)
    {
        $adminId = Session::get('admin_id');

        if ($adminId) {
            Log::info('Logout para administrador_id: ' . $adminId);
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

        // Tabla: users → usuarios
        // Columnas: plate_number → numero_placa, active → activo
        $user = DB::table('usuarios')
            ->where('numero_placa', strtoupper(trim($request->plate_number)))
            ->where('activo', 1)
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
            // Columnas: user_id → usuario_id, last_login → ultimo_acceso, updated_at → fecha_actualizacion
            DB::table('usuarios')
                ->where('usuario_id', $user->usuario_id)
                ->update(['ultimo_acceso' => now(), 'fecha_actualizacion' => now()]);

            Log::info('Login exitoso para usuario_id: ' . $user->usuario_id);

        } catch (\Exception $e) {
            Log::error('Error al actualizar ultimo_acceso: ' . $e->getMessage());
        }

        // Columnas: user_id → usuario_id, first_name → nombre, last_name → apellido, 
        // plate_number → numero_placa, points → puntos, vehicle_brand → marca_vehiculo, 
        // vehicle_model → modelo_vehiculo
        Session::put('user_id', $user->usuario_id);
        Session::put('user_name', $user->nombre . ' ' . $user->apellido);
        Session::put('user_email', $user->email);
        Session::put('user_plate', $user->numero_placa);
        Session::put('user_points', $user->puntos);
        Session::put('user_vehicle', $user->marca_vehiculo . ' ' . $user->modelo_vehiculo);
        Session::put('user_role', 'usuario');

        return redirect()->route('usuario.dashboard')
            ->with('success', '¡Bienvenido ' . $user->nombre . '!');
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
            'email' => 'required|email|max:100|unique:usuarios,email',
            'plate_number' => 'required|string|max:20|unique:usuarios,numero_placa',
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
            'vehicle_model.required' => 'El modelo del vehículo es obligatoria.',
            'vehicle_year.required' => 'El año del vehículo es obligatorio.'
        ]);

        try {
            $tempPassword = Str::random(8);
            
            // Tabla: users → usuarios
            // Columnas: first_name → nombre, last_name → apellido, plate_number → numero_placa,
            // phone → telefono, vehicle_brand → marca_vehiculo, vehicle_model → modelo_vehiculo,
            // vehicle_year → anio_vehiculo, points → puntos, active → activo,
            // created_at → fecha_creacion, updated_at → fecha_actualizacion
            $userId = DB::table('usuarios')->insertGetId([
                'nombre' => $request->first_name,
                'apellido' => $request->last_name,
                'email' => $request->email,
                'numero_placa' => strtoupper(trim($request->plate_number)),
                'password' => $tempPassword, 
                'telefono' => $request->phone,
                'marca_vehiculo' => $request->vehicle_brand,
                'modelo_vehiculo' => $request->vehicle_model,
                'anio_vehiculo' => $request->vehicle_year,
                'puntos' => 100,
                'activo' => 1,
                'fecha_creacion' => now(),
                'fecha_actualizacion' => now()
            ]);

            // Tabla: point_transactions → transacciones_puntos
            // Columnas: user_id → usuario_id, type → tipo, points → puntos,
            // description → descripcion, created_at → fecha_creacion, updated_at → fecha_actualizacion
            DB::table('transacciones_puntos')->insert([
                'usuario_id' => $userId,
                'tipo' => 'ganado',
                'puntos' => 100,
                'descripcion' => 'Puntos de bienvenida por registro',
                'fecha_creacion' => now(),
                'fecha_actualizacion' => now()
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

            $user = DB::table('usuarios')->where('usuario_id', $userId)->first();
            
            Session::put('user_id', $user->usuario_id);
            Session::put('user_name', $user->nombre . ' ' . $user->apellido);
            Session::put('user_email', $user->email);
            Session::put('user_plate', $user->numero_placa);
            Session::put('user_points', $user->puntos);
            Session::put('user_vehicle', $user->marca_vehiculo . ' ' . $user->modelo_vehiculo);
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
            Log::info('Logout para usuario_id: ' . $userId);
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

        // Tabla: users → usuarios
        // Columnas: plate_number → numero_placa, active → activo
        $user = DB::table('usuarios')
            ->where('email', $request->email)
            ->where('numero_placa', strtoupper(trim($request->plate_number)))
            ->where('activo', 1)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'No encontramos un usuario con esos datos.'
            ]);
        }

        try {
            $tempPassword = Str::random(10);
            
            // Columnas: user_id → usuario_id, updated_at → fecha_actualizacion
            DB::table('usuarios')
                ->where('usuario_id', $user->usuario_id)
                ->update([
                    'password' => $tempPassword,
                    'fecha_actualizacion' => now()
                ]);

            Mail::send('emails.password-reset-user', [
                'userName' => $user->nombre,
                'plateNumber' => $user->numero_placa,
                'tempPassword' => $tempPassword
            ], function($message) use ($user) {
                $message->to($user->email)
                        ->subject('Recuperación de Contraseña - Sistema de Recompensas');
            });

            Log::info('Contraseña restablecida para usuario_id: ' . $user->usuario_id);

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

        // Tablas: redemptions → canjes_recompensas, rewards → recompensas
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