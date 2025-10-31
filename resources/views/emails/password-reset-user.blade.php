<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Montserrat', Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <!-- Contenedor principal -->
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #6B1C3D 0%, #8B2450 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 700;">
                                🔐 Recuperación de Contraseña
                            </h1>
                            <p style="color: #ffffff; margin: 10px 0 0 0; font-size: 16px; opacity: 0.95;">
                                Sistema de Recompensas por Conducción Responsable
                            </p>
                        </td>
                    </tr>

                    <!-- Contenido principal -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #6B1C3D; font-size: 22px; margin-top: 0; margin-bottom: 20px;">
                                Hola, {{ $userName }}
                            </h2>
                            
                            <p style="color: #333333; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">
                                Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en el <strong>Sistema de Recompensas</strong>.
                            </p>

                            <p style="color: #333333; font-size: 16px; line-height: 1.6; margin-bottom: 30px;">
                                Tu nueva contraseña temporal es:
                            </p>

                            <!-- Credenciales -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f9fa; border-left: 4px solid #6B1C3D; border-radius: 6px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <p style="margin: 0 0 15px 0; color: #6B1C3D; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                            🚗 Placa del Vehículo
                                        </p>
                                        <p style="margin: 0 0 25px 0; color: #333333; font-size: 18px; font-weight: 600;">
                                            {{ $plateNumber }}
                                        </p>

                                        <p style="margin: 0 0 15px 0; color: #6B1C3D; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                            🔑 Nueva Contraseña Temporal
                                        </p>
                                        <p style="margin: 0; color: #333333; font-size: 24px; font-weight: 700; font-family: 'Courier New', monospace; background-color: #ffffff; padding: 15px; border-radius: 4px; border: 2px dashed #6B1C3D; text-align: center; letter-spacing: 2px;">
                                            {{ $tempPassword }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Alerta de seguridad -->
                            <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; border-radius: 6px; padding: 20px; margin-bottom: 30px;">
                                <p style="margin: 0 0 10px 0; color: #856404; font-size: 14px; font-weight: 600;">
                                    ⚠️ RECOMENDACIÓN DE SEGURIDAD
                                </p>
                                <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.6;">
                                    Por tu seguridad, te recomendamos cambiar esta contraseña temporal después de iniciar sesión. Accede a tu perfil para actualizarla.
                                </p>
                            </div>

                            <!-- Botón de acción -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/login-usuario') }}" style="display: inline-block; background-color: #6B1C3D; color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 6px; font-size: 16px; font-weight: 700; box-shadow: 0 4px 12px rgba(107, 28, 61, 0.3);">
                                            🚀 Iniciar Sesión
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Aviso de seguridad -->
                            <div style="background-color: #f8d7da; border-left: 4px solid #dc3545; border-radius: 6px; padding: 20px; margin-bottom: 20px;">
                                <p style="margin: 0 0 10px 0; color: #721c24; font-size: 14px; font-weight: 600;">
                                    🔒 ¿No solicitaste este cambio?
                                </p>
                                <p style="margin: 0; color: #721c24; font-size: 14px; line-height: 1.6;">
                                    Si no solicitaste restablecer tu contraseña, ignora este correo. Tu cuenta permanece segura y tu contraseña anterior sigue siendo válida.
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 30px; text-align: center; border-top: 3px solid #6B1C3D;">
                            <p style="margin: 0 0 10px 0; color: #6B1C3D; font-size: 16px; font-weight: 600;">
                                Gobierno Federal de México
                            </p>
                            <p style="margin: 0 0 15px 0; color: #666666; font-size: 14px;">
                                Sistema de Recompensas por Conducción Responsable
                            </p>
                            <p style="margin: 0 0 20px 0; color: #999999; font-size: 12px; line-height: 1.6;">
                                Para soporte técnico, escríbenos a: soporte@sistema-recompensas.gob.mx<br>
                                O llámanos al: 55-5555-5555
                            </p>
                            <p style="margin: 0; color: #999999; font-size: 11px;">
                                © 2025 Gobierno Federal de México. Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>