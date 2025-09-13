<!DOCTYPE html>
<html>
<head>
    <title>Clases Disponibles</title>
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .alert-error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .user-info {
            background-color: #e7f3ff;
            border: 1px solid #b3d9ff;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .clases-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .clase-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .clase-title {
            color: #495057;
            margin-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }
        .nav-links {
            margin-bottom: 20px;
        }
        .nav-links a, .nav-links button {
            text-decoration: none;
            padding: 8px 16px;
            margin-right: 10px;
            border-radius: 4px;
            display: inline-block;
        }
        .btn-primary { background-color: #007bff; color: white; border: none; }
        .btn-secondary { background-color: #6c757d; color: white; border: none; }
        .btn-danger { background-color: #dc3545; color: white; border: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Clases Disponibles</h1>
        
        {{-- Información del usuario logueado --}}
        @if(session('api_user'))
            @php $user = session('api_user'); @endphp
            <div class="user-info">
                <strong>Usuario:</strong> {{ $user['nombre'] }} {{ $user['apellidos'] }} ({{ $user['rol'] }})
            </div>
        @endif

        {{-- Enlaces de navegación --}}
        <div class="nav-links">
            <a href="{{ route('demo.bienvenida') }}" class="btn-secondary">← Volver a Bienvenida</a>
            
            <form method="POST" action="{{ route('demo.logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn-danger" onclick="return confirm('¿Cerrar sesión?')">
                    Cerrar sesión
                </button>
            </form>
        </div>

        {{-- Mostrar errores --}}
        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Mostrar las clases --}}
        @if(isset($clases) && count($clases) > 0)
            <p><strong>Total de clases:</strong> {{ count($clases) }}</p>
            
            <div class="clases-grid">
                @foreach($clases as $clase)
                    <div class="clase-card">
                        <h3 class="clase-title">{{ $clase['nombre'] ?? 'Sin nombre' }}</h3>
                        
                        @if(isset($clase['descripcion']))
                            <p><strong>Descripción:</strong> {{ $clase['descripcion'] }}</p>
                        @endif
                        
                        @if(isset($clase['profesor']))
                            <p><strong>Profesor:</strong> {{ $clase['profesor'] }}</p>
                        @endif
                        
                        @if(isset($clase['horario']))
                            <p><strong>Horario:</strong> {{ $clase['horario'] }}</p>
                        @endif
                        
                        @if(isset($clase['created_at']))
                            <p><small>Creada: {{ date('d/m/Y', strtotime($clase['created_at'])) }}</small></p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 40px; background-color: #f8f9fa; border-radius: 8px;">
                <h3>No hay clases disponibles</h3>
                <p>Actualmente no se encontraron clases en el sistema.</p>
            </div>
        @endif
    </div>
</body>
</html>