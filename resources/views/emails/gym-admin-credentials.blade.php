<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; border-radius: 4px 4px 0 0; }
        .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 0 0 4px 4px; }
        .credentials-box { background: white; border-left: 4px solid #28a745; padding: 15px; margin: 20px 0; }
        .credentials-box strong { color: #007bff; }
        .footer { font-size: 12px; color: #666; margin-top: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bienvenido a {{ config('app.name') }}</h1>
        </div>

        <div class="content">
            <p>Hola,</p>

            <p>Tu gimnasio <strong>{{ $gimnasioNombre }}</strong> ha sido registrado exitosamente en nuestra plataforma.</p>

            <p>A continuación encontrarás tus credenciales de acceso como administrador:</p>

            <div class="credentials-box">
                <p>
                    <strong>Email:</strong><br>
                    {{ $email }}
                </p>
                <p>
                    <strong>Contraseña temporal:</strong><br>
                    <code style="background: #f0f0f0; padding: 5px 10px; border-radius: 3px; display: inline-block;">{{ $password }}</code>
                </p>
            </div>

            <p><strong>Instrucciones:</strong></p>
            <ol>
                <li>Accede a tu panel de administrador con las credenciales anteriores</li>
                <li>En tu primer ingreso, se te pedirá cambiar esta contraseña temporal por una más segura</li>
                <li>Guarda tus nuevas credenciales en un lugar seguro</li>
            </ol>

            <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>

            <div class="footer">
                <p>© {{ now()->year }} {{ config('app.name') }}. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>
</body>
</html>
