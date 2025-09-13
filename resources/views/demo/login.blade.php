<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Demo Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="col-md-4">
      <div class="card shadow-lg rounded-4">
        <div class="card-body">
          <h3 class="card-title text-center mb-4">Login API Demo</h3>

          {{-- Mensajes de estado --}}
          @if (session('status'))
          <div class="alert alert-success">{{ session('status') }}</div>
          @endif

          @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $e)
              <li>{{ $e }}</li>
              @endforeach
            </ul>
          </div>
          @endif

          {{-- Formulario --}}
          <form method="POST" action="{{ route('demo.login.do') }}">
            @csrf
            <div class="mb-3">
  <label for="email" class="form-label">Correo electrónico</label>
  <input type="email" name="email" id="email"
         class="form-control @error('email') is-invalid @enderror"
         value="{{ old('email') }}" required>
  @error('email')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label for="password" class="form-label">Contraseña</label>
  <input type="password" name="password" id="password"
         class="form-control @error('password') is-invalid @enderror"
         required>
  @error('password')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>


            <button type="submit" class="btn btn-success w-100">Entrar</button>
            <p class="mt-3 text-center">
              ¿No tienes cuenta?
              <a href="{{ route('demo.register') }}">Regístrate aquí</a>
            </p>

          </form>

          
        </div>
      </div>
    </div>
  </div>
</body>

</html>