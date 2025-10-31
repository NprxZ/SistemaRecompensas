<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido al Sistema de Recompensas</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Montserrat', Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <!-- Contenedor principal -->
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
                    
                    <!-- Header con colores gubernamentales -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #6B1C3D 0%, #8B2450 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 700;">
                                🎉 ¡Bienvenido!
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
                                Hola, {{ $userName }} 👋
                            </h2>
                            
                            <p style="color: #333333; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">
                                ¡Gracias por unirte al <strong>Programa de Conducción Responsable</strong> del Gobierno Federal de México! Estamos emocionados de tenerte con nosotros.
                            </p>

                            <p style="color: #333333; font-size: 16px; line-height: 1.6; margin-bottom: 30px;">
                                Tu cuenta ha sido creada exitosamente. A continuación, encontrarás tus credenciales de acceso:
                            </p>

                            <!-- Credenciales en caja destacada -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f9fa; border-left: 4px solid #6B1C3D; border-radius: 6px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <p style="margin: 0 0 15px 0; color: #6B1C3D; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                            📧 Correo Electrónico
                                        </p>
                                        <p style="margin: 0 0 25px 0; color: #333333; font-size: 18px; font-weight: 600;">
                                            {{ $email }}
                                        </p>

                                        <p style="margin: 0 0 15px 0; color: #6B1C3D; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                            🚗 Placa del Vehículo
                                        </p>
                                        <p style="margin: 0 0 25px 0; color: #333333; font-size: 18px; font-weight: 600;">
                                            {{ $plateNumber }}
                                        </p>

                                        <p style="margin: 0 0 15px 0; color: #6B1C3D; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                            🔑 Contraseña Temporal
                                        </p>
                                        <p style="margin: 0; color: #333333; font-size: 24px; font-weight: 700; font-family: 'Courier New', monospace; background-color: #ffffff; padding: 15px; border-radius: 4px; border: 2px dashed #6B1C3D; text-align: center; letter-spacing: 2px;">
                                            {{ $tempPassword }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Puntos de bienvenida -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background: linear-gradient(135deg, #6B1C3D 0%, #8B2450 100%); border-radius: 6px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 25px; text-align: center;">
                                        <p style="margin: 0 0 10px 0; color: #ffffff; font-size: 16px; opacity: 0.9;">
                                            ¡Regalo de Bienvenida! 🎁
                                        </p>
                                        <p style="margin: 0; color: #ffffff; font-size: 32px; font-weight: 700;">
                                            ⭐ 100 Puntos
                                        </p>
                                        <p style="margin: 10px 0 0 0; color: #ffffff; font-size: 14px; opacity: 0.9;">
                                            Ya puedes empezar a canjear recompensas
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Instrucciones -->
                            <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; border-radius: 6px; padding: 20px; margin-bottom: 30px;">
                                <p style="margin: 0 0 10px 0; color: #856404; font-size: 14px; font-weight: 600;">
                                    ⚠️ IMPORTANTE
                                </p>
                                <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.6;">
                                    Por seguridad, te recomendamos cambiar tu contraseña después de tu primer inicio de sesión. Puedes hacerlo desde tu perfil.
                                </p>
                            </div>

                            <!-- Botón de acción -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/login-usuario') }}" style="display: inline-block; background-color: #6B1C3D; color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 6px; font-size: 16px; font-weight: 700; box-shadow: 0 4px 12px rgba(107, 28, 61, 0.3);">
                                            🚀 Iniciar Sesión Ahora
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Beneficios -->
                            <h3 style="color: #6B1C3D; font-size: 18px; margin-bottom: 15px;">
                                🎯 Beneficios del Programa
                            </h3>
                            <ul style="color: #333333; font-size: 15px; line-height: 1.8; padding-left: 20px;">
                                <li>Acumula puntos por conducción responsable</li>
                                <li>Descuentos en gasolina y mantenimiento</li>
                                <li>Recompensas exclusivas para conductores ejemplares</li>
                                <li>Participa en rankings nacionales</li>
                                <li>Contribuye a vías más seguras</li>
                            </ul>
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
                                Si no solicitaste esta cuenta, puedes ignorar este correo de forma segura.<br>
                                Para soporte técnico, escríbenos a: soporte@sistema-recompensas.gob.mx
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