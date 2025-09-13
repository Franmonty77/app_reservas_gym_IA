<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mis Reservas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
<div class="row">
<div class="col-12">
<div class="d-flex justify-content-between align-items-center mb-4">
<h1><i class="fas fa-calendar-check"></i> Mis Reservas</h1>
<a href="{{ route('reservas.create') }}" class="btn btn-primary">
<i class="fas fa-plus"></i> Nueva Reserva
</a>
</div>

{{-- Mensajes de éxito y error --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
<i class="fas fa-check-circle"></i> {{ session('success') }}
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
<i class="fas fa-exclamation-circle"></i> {{ session('error') }}
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Información del usuario --}}
<div class="alert alert-info">
<strong>Usuario:</strong> {{ session('api_user')['nombre'] ?? 'Usuario' }} {{ session('api_user')['apellidos'] ?? '' }}
({{ session('api_user')['email'] ?? 'email' }})
</div>

{{-- Lista de Reservas --}}
@if(isset($reservas) && count($reservas) > 0)
<div class="row">
@foreach($reservas as $reserva)
<div class="col-md-6 mb-3">
<div class="card">
<div class="card-body">
<h5 class="card-title">{{ $reserva->clase_nombre }}</h5>
<p class="card-text">
<strong>Fecha:</strong> {{ date('d/m/Y', strtotime($reserva->fecha_inicio)) }}<br>
<strong>Hora:</strong> {{ date('H:i', strtotime($reserva->fecha_inicio)) }} - {{ date('H:i', strtotime($reserva->fecha_fin)) }}<br>
@if($reserva->entrenador_nombre)
<strong>Entrenador:</strong> {{ $reserva->entrenador_nombre }}<br>
@endif
<strong>Estado:</strong>
@if($reserva->estado == 'confirmada')
<span class="badge bg-success">Confirmada</span>
@elseif($reserva->estado == 'cancelada')
<span class="badge bg-danger">Cancelada</span>
@else
<span class="badge bg-warning">{{ ucfirst($reserva->estado) }}</span>
@endif
</p>
@if($reserva->estado == 'confirmada')
<button class="btn btn-sm btn-outline-danger" onclick="if(confirm('¿Cancelar reserva?')) { /* cancelar reserva */ }">
<i class="fas fa-times"></i> Cancelar
</button>
@endif
</div>
</div>
</div>
@endforeach
</div>
@else
<div class="alert alert-warning text-center">
<h4><i class="fas fa-calendar-times"></i> No tienes reservas</h4>
<p>Aún no has realizado ninguna reserva.</p>
<p>¡Haz tu primera reserva usando el botón de arriba!</p>
</div>
@endif

{{-- Botón volver --}}
<div class="mt-4">
<a href="{{ route('demo.bienvenida') }}" class="btn btn-secondary">
<i class="fas fa-arrow-left"></i> Volver a Bienvenida
</a>
</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>