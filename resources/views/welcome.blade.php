<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TratoAgro - Sistema de Gestión Agrícola</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }
        .container {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 600px;
        }
        h1 {
            color: #667eea;
            margin-bottom: 1rem;
            font-size: 2.5rem;
        }
        .subtitle {
            color: #666;
            margin-bottom: 2rem;
            font-size: 1.2rem;
        }
        .info {
            background: #f7f7f7;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        .info h2 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }
        .info ul {
            list-style: none;
            text-align: left;
        }
        .info li {
            padding: 0.5rem 0;
            color: #555;
        }
        .info li:before {
            content: "✓ ";
            color: #667eea;
            font-weight: bold;
            margin-right: 0.5rem;
        }
        .status {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            margin-top: 1rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🌾 TratoAgro</h1>
        <p class="subtitle">Sistema de Gestión Agrícola</p>
        <div class="status">✓ Sistema Operativo</div>
        
        <div class="info">
            <h2>Arquitectura del Proyecto</h2>
            <ul>
                <li>Laravel 12</li>
                <li>Docker & Docker Compose</li>
                <li>Arquitectura Limpia (Clean Architecture)</li>
                <li>ORM con Eloquent</li>
                <li>Repositorios y Servicios</li>
                <li>PostgreSQL 16</li>
                <li>Redis</li>
                <li>Nginx</li>
            </ul>
        </div>
    </div>
</body>
</html>

