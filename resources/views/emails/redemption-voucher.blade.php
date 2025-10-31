<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Canje</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #6B1C3D 0%, #9B3558 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 700;
        }
        .header p {
            font-size: 16px;
            opacity: 0.95;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #6B1C3D;
            padding: 20px;
            margin: 25px 0;
            border-radius: 5px;
        }
        .info-box h3 {
            color: #6B1C3D;
            font-size: 18px;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: bold;
            color: #6B1C3D;
        }
        .info-value {
            color: #555;
        }
        .code-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px dashed #6B1C3D;
            padding: 25px;
            text-align: center;
            margin: 25px 0;
            border-radius: 8px;
        }
        .code-box p {
            color: #6B1C3D;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .code-box h2 {
            color: #6B1C3D;
            font-size: 32px;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            font-weight: 800;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            margin: 10px 0;
        }
        .status-approved {
            background: #28a745;
            color: white;
        }
        .status-pending {
            background: #ffc107;
            color: #212529;
        }
        .instructions {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin: 25px 0;
            border-radius: 5px;
        }
        .instructions h4 {
            color: #856404;
            margin-bottom: 12px;
            font-size: 16px;
        }
        .instructions ul {
            color: #856404;
            padding-left: 20px;
            line-height: 1.8;
        }
        .cta-button {
            display: inline-block;
            background: #6B1C3D;
            color: white;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            border-top: 3px solid #6B1C3D;
        }
        .footer p {
            margin: 8px 0;
            line-height: 1.6;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #6B1C3D;
            text-decoration: none;
        }
        .divider {
            height: 2px;
            background: linear-gradient(to right, transparent, #6B1C3D, transparent);
            margin: 30px 0;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                border-radius: 0;
            }
            .content {
                padding: 30px 20px;
            }
            .header {
                padding: 30px 20px;
            }
            .header h1 {
                font-size: 24px;
            }
            .code-box h2 {
                font-size: 24px;
                letter-spacing: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🎁 ¡Tu Canje está Listo!</h1>
            <p>Comprobante de Canje #{{ $redemption->redemption_id }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p class="greeting">
                Hola <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>,
            </p>
            <p class="greeting">
                ¡Excelentes noticias! Tu solicitud de canje ha sido procesada exitosamente. 
                En este correo encontrarás tu comprobante en PDF adjunto y toda la información necesaria.
            </p>

            <!-- Código de Canje -->
            <div class="code-box">
                <p>TU CÓDIGO DE CANJE</p>
                <h2>{{ $redemption->redemption_code }}</h2>
                <p style="margin-top: 10px; font-size: 12px; font-weight: normal;">
                    Presenta este código para reclamar tu recompensa
                </p>
            </div>

            <!-- Información del Canje -->
            <div class="info-box">
                <h3>📦 Detalles de tu Recompensa</h3>
                <div class="info-row">
                    <span class="info-label">Recompensa:</span>
                    <span class="info-value">{{ $redemption->title }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Puntos Utilizados:</span>
                    <span class="info-value">⭐ {{ number_format($redemption->points_used) }} puntos</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Fecha de Solicitud:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($redemption->created_at)->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Estado:</span>
                    <span class="info-value">
                        @if($redemption->status == 'approved')
                            <span class="status-badge status-approved">✅ APROBADO</span>
                        @elseif($redemption->status == 'pending')
                            <span class="status-badge status-pending">⏳ PENDIENTE</span>
                        @endif
                    </span>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Instrucciones -->
            <div class="instructions">
                <h4>📋 ¿Cómo reclamar tu recompensa?</h4>
                <ul>
                    <li>Presenta el comprobante adjunto (impreso o digital)</li>
                    <li>Muestra tu código de canje o escanea el código QR</li>
                    <li>Verifica que tu identificación coincida con los datos registrados</li>
                    <li>El personal validará y te entregará tu recompensa</li>
                </ul>
            </div>

            <p style="color: #555; line-height: 1.8; margin: 25px 0;">
                <strong>Nota importante:</strong> Tu comprobante en PDF está adjunto a este correo. 
                Puedes imprimirlo o mostrarlo desde tu dispositivo móvil.
            </p>

            @if($redemption->terms_conditions)
            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <p style="font-size: 12px; color: #6c757d; line-height: 1.6;">
                    <strong>Términos y Condiciones:</strong><br>
                    {{ $redemption->terms_conditions }}
                </p>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Sistema de Recompensas por Puntos</strong></p>
            <p>Gracias por ser parte de nuestro programa de lealtad</p>
            
            <div class="divider" style="margin: 20px 40px;"></div>
            
            <p style="font-size: 12px; color: #999; margin-top: 15px;">
                Este correo fue enviado automáticamente. Por favor no respondas a este mensaje.<br>
                Si tienes alguna pregunta, contáctanos a través de nuestros canales oficiales.
            </p>
            
            <p style="font-size: 12px; color: #999; margin-top: 10px;">
                © {{ date('Y') }} Sistema de Recompensas. Todos los derechos reservados.
            </p>
        </div>
    </div>
</body>
</html>