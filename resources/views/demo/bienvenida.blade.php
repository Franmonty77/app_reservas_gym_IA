<!DOCTYPE html>
<html>
<head>
    <title>Bienvenida</title>
</head>
<body>
    <h1>¡Bienvenido!</h1>
    
    @if(session('status'))
        <div style="background-color: #d4edda; padding: 10px; margin-bottom: 15px; border: 1px solid #c3e6cb; color: #155724;">
            {{ session('status') }}
        </div>
    @endif
    
    @if(session('api_user'))
        @php $user = session('api_user'); @endphp
        <div style="background-color: #f8f9fa; padding: 15px; margin: 10px 0; border: 1px solid #dee2e6;">
            <h3>Datos del usuario:</h3>
            <p><strong>Nombre:</strong> {{ $user['nombre'] }} {{ $user['apellidos'] }}</p>
            <p><strong>Email:</strong> {{ $user['email'] }}</p>
            <p><strong>Teléfono:</strong> {{ $user['telefono'] }}</p>
            <p><strong>Rol:</strong> {{ $user['rol'] }}</p>
            <p><strong>Estado:</strong> {{ $user['estado'] }}</p>
        </div>
    @endif

    <a href="{{ route('clases.web') }}" style="text-decoration: none; padding: 10px 20px; background-color: #28a745; color: white; border-radius: 4px; margin-right: 10px;">Ver Clases</a>
    <a href="{{ route('sesiones.index') }}" style="text-decoration: none; padding: 10px 20px; background-color: #2874a7ff; color: white; border-radius: 4px; margin-right: 10px;">Ver Sesiones</a>
    <a href="{{ route('reservas.index') }}" style="text-decoration: none; padding: 10px 20px; background-color: #8ca728ff; color: white; border-radius: 4px; margin-right: 10px;">Ver Reservas</a>
    
     {{-- Formulario de logout --}}
        <form method="POST" action="{{ route('demo.logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="logout-btn" onclick="return confirm('¿Estás seguro de que quieres cerrar sesión?')">
                Cerrar sesión
            </button>
        </form>
</body>
</html>