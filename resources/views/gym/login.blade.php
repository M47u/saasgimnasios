<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Gimnasio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0d2b21 0%, #1D9E75 100%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        .login-card {
            width: 100%; max-width: 420px;
            border: none; border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
        }
        .login-header {
            background: #1D9E75; color: #fff;
            padding: 2rem; border-radius: 16px 16px 0 0; text-align: center;
        }
        .login-header .icon { font-size: 2.5rem; margin-bottom: .5rem; }
        .form-control:focus { border-color: #1D9E75; box-shadow: 0 0 0 .25rem rgba(29,158,117,.25); }
        .form-select:focus { border-color: #1D9E75; box-shadow: 0 0 0 .25rem rgba(29,158,117,.25); }
        .btn-login {
            background: #1D9E75; color: #fff; width: 100%;
            padding: .75rem; font-weight: 600; border: none; border-radius: 8px;
        }
        .btn-login:hover { background: #157a5a; color: #fff; }
    </style>
</head>
<body>
    <div class="login-card card">
        <div class="login-header">
            <div class="icon"><i class="bi bi-lightning-charge-fill"></i></div>
            <h4 class="mb-0 fw-bold">Panel del Gimnasio</h4>
            <p class="mb-0 opacity-75 small mt-1">Acceso para staff</p>
        </div>
        <div class="card-body p-4">
            @if($errors->any())
                <div class="alert alert-danger py-2">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('gym.login.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="admin@gimnasio.com"
                               required autofocus>
                    </div>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="••••••••" required>
                    </div>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
                </button>
            </form>
        </div>
    </div>
</body>
</html>
