<!-- resources/views/demo/register.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Demo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Opcional: Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container" style="max-width: 520px;">
        <h1 class="mb-3">Crear cuenta</h1>

        {{-- Mensaje de éxito --}}
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        {{-- Errores de validación --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Corrige los siguientes campos:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{!! $error !!}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('demo.register.submit') }}" novalidate>
    @csrf

    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Apellidos</label>
        <input type="text" name="ape" class="form-control" value="{{ old('ape') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Teléfono</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
    </div>

    <div class="mb-3">
    <label class="form-label">Rol</label>
    <select name="rol" class="form-select" required>
        <option value="">-- Selecciona un rol --</option>
        <option value="alumno" {{ old('rol')=='alumno' ? 'selected' : '' }}>Alumno</option>
        <option value="entrenador" {{ old('rol')=='entrenador' ? 'selected' : '' }}>Entrenador</option>
    </select>
</div>


    <div class="mb-3">
        <label class="form-label">Contraseña</label>
        <input type="password" name="password" class="form-control" required>
        <div class="form-text">Mínimo 8 caracteres, con letras y números.</div>
    </div>

    <div class="mb-3">
        <label class="form-label">Confirmar contraseña</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success w-100">Registrarse</button>
    
    <p class="mt-3 text-center">
    ¿Ya tienes cuenta? 
    <a href="{{ route('demo.login') }}">Inicia sesión aquí</a>
</p>

</form>
    </div>
</body>
</html>
