<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('/imagenes/fondologin.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background-color: rgba(255, 255, 255, 0.95); 
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
        }

        .login-box h3 {
            margin-bottom: 25px;
        }

        .btn-success {
            background-color: #0000FF; 
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-success:hover {
            background-color: #2B2BFF; 
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h3 class="text-center">Iniciar Sesión</h3>

        {{-- Mostrar errores --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            {{-- Usuario --}}
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" 
                       class="form-control @error('usuario') is-invalid @enderror" 
                       id="usuario" 
                       name="usuario" 
                       value="{{ old('usuario') }}" 
                       required>
                @error('usuario')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Contraseña --}}
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       required>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <button type="submit" class="btn btn-success w-100">Ingresar</button>
        </form>
    </div>
</body>
</html>



