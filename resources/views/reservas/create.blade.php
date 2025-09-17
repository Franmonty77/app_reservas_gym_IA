<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Nueva Reserva</h4>
                        <a href="{{ route('reservas.index') }}" class="btn btn-sm btn-outline-secondary">← Volver</a>
                    </div>
                    
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('reservas.store') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_reserva" class="form-label">Fecha *</label>
                                    <input type="date" 
                                           class="form-control @error('fecha_reserva') is-invalid @enderror" 
                                           id="fecha_reserva" 
                                           name="fecha_reserva" 
                                           value="{{ old('fecha_reserva') }}"
                                           min="{{ date('Y-m-d') }}"
                                           required>
                                    @error('fecha_reserva')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="hora_reserva" class="form-label">Hora *</label>
                                    <input type="time" 
                                           class="form-control @error('hora_reserva') is-invalid @enderror" 
                                           id="hora_reserva" 
                                           name="hora_reserva" 
                                           value="{{ old('hora_reserva') }}"
                                           required>
                                    @error('hora_reserva')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select class="form-control @error('estado') is-invalid @enderror" 
                                            id="estado" 
                                            name="estado">
                                        <option value="confirmada" {{ old('estado', 'confirmada') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                        <option value="en_espera" {{ old('estado') == 'en_espera' ? 'selected' : '' }}>En Espera</option>
                                        <option value="cancelada" {{ old('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                    </select>
                                    @error('estado')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="posicion_espera" class="form-label">Posición en Espera</label>
                                    <input type="number" 
                                           class="form-control @error('posicion_espera') is-invalid @enderror" 
                                           id="posicion_espera" 
                                           name="posicion_espera" 
                                           value="{{ old('posicion_espera') }}"
                                           min="1">
                                    @error('posicion_espera')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fuente" class="form-label">Fuente</label>
                                    <select class="form-control @error('fuente') is-invalid @enderror" 
                                            id="fuente" 
                                            name="fuente">
                                        <option value="web" {{ old('fuente', 'web') == 'web' ? 'selected' : '' }}>Web</option>
                                        <option value="app" {{ old('fuente') == 'app' ? 'selected' : '' }}>App</option>
                                        <option value="admin" {{ old('fuente') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    @error('fuente')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('reservas.index') }}" class="btn btn-secondary">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Crear Reserva
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