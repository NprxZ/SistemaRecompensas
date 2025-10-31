<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Canje</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            padding: 40px;
            color: #333;
        }
        .header {
            background: linear-gradient(135deg, #6B1C3D 0%, #9B3558 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            border: 3px solid #6B1C3D;
        }
        .code-section {
            text-align: center;
            background: white;
            padding: 30px;
            border-radius: 8px;
            margin: 20px 0;
            border: 2px dashed #6B1C3D;
        }
        .code-section h2 {
            color: #6B1C3D;
            font-size: 36px;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            margin: 20px 0;
        }
        .qr-section {
            text-align: center;
            margin: 20px 0;
        }
        .qr-section img {
            width: 200px;
            height: 200px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin: 20px 0;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            padding: 12px;
            font-weight: bold;
            color: #6B1C3D;
            width: 40%;
            background: #f0f0f0;
            border-bottom: 1px solid #ddd;
        }
        .info-value {
            display: table-cell;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        .reward-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .reward-info h3 {
            color: #6B1C3D;
            margin-bottom: 10px;
            font-size: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
        }
        .status-approved {
            background: #28a745;
            color: white;
        }
        .status-pending {
            background: #ffc107;
            color: #212529;
        }
        .status-delivered {
            background: #17a2b8;
            color: white;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            padding-top: 20px;
            border-top: 2px solid #6B1C3D;
        }
        .instructions {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .instructions h4 {
            color: #856404;
            margin-bottom: 10px;
        }
        .instructions p {
            color: #856404;
            font-size: 13px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>🎁 COMPROBANTE DE CANJE</h1>
        <p>Sistema de Recompensas por Puntos</p>
    </div>

    <!-- Content -->
    <div class="content">
        <!-- Código de Canje -->
        <div class="code-section">
            <p style="color: #6B1C3D; font-weight: bold; margin-bottom: 10px;">CÓDIGO DE CANJE</p>
            <h2>{{ $redemption->redemption_code }}</h2>
            
            <!-- QR Code -->
            <div class="qr-section">
                <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code">
                <p style="font-size: 12px; color: #6c757d; margin-top: 10px;">
                    Escanea este código para verificar
                </p>
            </div>
        </div>

        <!-- Información del Canje -->
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">ID de Canje:</div>
                <div class="info-value">#{{ $redemption->redemption_id }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Usuario:</div>
                <div class="info-value">{{ $user->first_name }} {{ $user->last_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Placa:</div>
                <div class="info-value">{{ $user->plate_number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha de Solicitud:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($redemption->created_at)->format('d/m/Y H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Estado:</div>
                <div class="info-value">
                    @if($redemption->status == 'approved')
                        <span class="status-badge status-approved">✅ APROBADO</span>
                    @elseif($redemption->status == 'pending')
                        <span class="status-badge status-pending">⏳ PENDIENTE</span>
                    @elseif($redemption->status == 'delivered')
                        <span class="status-badge status-delivered">📦 ENTREGADO</span>
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Puntos Utilizados:</div>
                <div class="info-value" style="color: #6B1C3D; font-weight: bold;">
                    ⭐ {{ number_format($redemption->points_used) }} puntos
                </div>
            </div>
        </div>

        <!-- Información de la Recompensa -->
        <div class="reward-info">
            <h3>📦 Recompensa</h3>
            <p style="font-size: 18px; font-weight: bold; margin: 10px 0;">
                {{ $redemption->title }}
            </p>
            <p style="color: #6c757d; font-size: 14px; line-height: 1.6;">
                {{ $redemption->description }}
            </p>
            @if($redemption->category)
                <p style="margin-top: 10px;">
                    <span style="background: #6B1C3D; color: white; padding: 5px 12px; border-radius: 15px; font-size: 11px;">
                        {{ strtoupper($redemption->category) }}
                    </span>
                </p>
            @endif
        </div>

        <!-- Instrucciones -->
        <div class="instructions">
            <h4>📋 Instrucciones para Canjear</h4>
            <p>
                1. Presenta este comprobante impreso o en formato digital<br>
                2. Muestra el código de canje o el código QR<br>
                3. Verifica que tu identificación coincida con los datos registrados<br>
                4. El personal validará y te entregará tu recompensa
            </p>
        </div>

        @if($redemption->terms_conditions)
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 20px;">
            <h4 style="color: #6B1C3D; font-size: 14px; margin-bottom: 8px;">
                Términos y Condiciones:
            </h4>
            <p style="font-size: 11px; color: #6c757d; line-height: 1.5;">
                {{ $redemption->terms_conditions }}
            </p>
        </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Sistema de Recompensas por Puntos</strong></p>
        <p>Documento generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        <p style="margin-top: 10px;">
            Este comprobante es válido únicamente para el canje indicado.<br>
            Para más información, contacta con nuestro equipo de soporte.
        </p>
    </div>
</body>
</html>