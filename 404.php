<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 404 - Señal Perdida</title>
    <style>
        body { 
            background-color: #0b0c10; /* Fondo oscuro espacial */
            color: #45a29e; 
            font-family: 'Courier New', Courier, monospace; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
            text-align: center;
        }
        .pantalla-radar {
            border: 2px solid #c5c6c7;
            padding: 50px;
            background: rgba(31, 40, 51, 0.8);
            box-shadow: 0 0 25px rgba(231, 76, 60, 0.4);
            max-width: 600px;
            border-radius: 8px;
        }
        h1 { 
            color: #e74c3c; 
            font-size: 100px; 
            margin: 0; 
            text-shadow: 0 0 10px rgba(231, 76, 60, 0.8); 
            line-height: 1;
        }
        h2 { color: #f39c12; margin-bottom: 30px; letter-spacing: 2px; }
        p { font-size: 18px; line-height: 1.6; color: #c5c6c7; }
        
        .btn-retorno {
            display: inline-block;
            margin-top: 30px;
            padding: 15px 30px;
            background: transparent;
            color: #66fcf1;
            text-decoration: none;
            border: 2px solid #66fcf1;
            font-size: 18px;
            font-weight: bold;
            transition: all 0.3s ease;
            text-transform: uppercase;
        }
        .btn-retorno:hover {
            background: #66fcf1;
            color: #0b0c10;
            box-shadow: 0 0 15px #66fcf1;
        }
        
        /* Animación de parpadeo para la alerta */
        .minovsky { 
            font-size: 14px; 
            color: #e74c3c; 
            margin-top: 40px; 
            animation: blink 2s infinite; 
            font-weight: bold;
        }
        @keyframes blink { 
            0% { opacity: 1; } 
            50% { opacity: 0.3; } 
            100% { opacity: 1; } 
        }
    </style>
</head>
<body>

    <div class="pantalla-radar">
        <h1>404</h1>
        <h2>ERROR DE NAVEGACIÓN</h2>
        <p><strong>[SISTEMA DE ALERTA ACTIVADO]</strong></p>
        <p>Las coordenadas ingresadas no corresponden a ningún sector registrado. Es posible que la colonia haya sido movida de su órbita o destruida.</p>
        
        <div class="minovsky">
            ⚠️ ALERTA: Alta concentración de Partículas Minovsky interfiriendo con el enlace. ⚠️
        </div>
        
        <a href="catalogo.php" class="btn-retorno">Recalcular Ruta al Hangar</a>
    </div>

</body>
</html>