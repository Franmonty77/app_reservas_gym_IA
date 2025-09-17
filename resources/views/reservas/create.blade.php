<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4><i class="fas fa-plus-circle"></i> Nueva Reserva</h4>
                        <a href="{{ route('reservas.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                    
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Errores encontrados:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('reservas.store') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fecha" class="form-label">
                                        <i class="fas fa-calendar-alt"></i> Fecha *
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('fecha') is-invalid @enderror" 
                                           id="fecha" 
                                           name="fecha" 
                                           value="{{ old('fecha') }}"
                                           min="{{ date('Y-m-d') }}"
                                           required>
                                    @error('fecha')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="hora" class="form-label">
                                        <i class="fas fa-clock"></i> Hora *
                                    </label>
                                    <input type="time" 
                                           class="form-control @error('hora') is-invalid @enderror" 
                                           id="hora" 
                                           name="hora" 
                                           value="{{ old('hora') }}"
                                           required>
                                    @error('hora')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="actividad" class="form-label">
                                        <i class="fas fa-dumbbell"></i> Actividad *
                                    </label>
                                    <select class="form-control @error('actividad') is-invalid @enderror" 
                                            id="actividad" 
                                            name="actividad"
                                            required>
                                        <option value="">Selecciona una actividad...</option>
                                        @if(isset($clases) && $clases->count() > 0)
                                            @foreach($clases as $clase)
                                                <option value="{{ $clase->nombre }}" 
                                                        {{ old('actividad') == $clase->nombre ? 'selected' : '' }}>
                                                    {{ $clase->nombre }}
                                                    @if($clase->descripcion)
                                                        - {{ $clase->descripcion }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="">No hay actividades disponibles</option>
                                        @endif
                                    </select>
                                    @error('actividad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Información adicional -->
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Información:</strong>
                                <ul class="mb-0 mt-1">
                                    <li>La reserva se confirmará automáticamente</li>
                                    <li>Duración estimada: 1 hora</li>
                                    <li>Capacidad máxima: 20 personas por clase</li>
                                </ul>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('reservas.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Crear Reserva
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>