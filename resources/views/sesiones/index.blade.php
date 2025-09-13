<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesiones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Sesiones</h1>
        
        <!-- Filtros simples -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{ route('sesiones.filter') }}" class="row g-3">
                    @csrf
                    <div class="col-md-3">
                        <label>Fecha:</label>
                        <input type="date" name="fecha" class="form-control" value="{{ session('filtros.fecha') }}">
                    </div>
                    <div class="col-md-2">
                        <label>Clase ID:</label>
                        <input type="number" name="clase_id" class="form-control" value="{{ session('filtros.clase_id') }}">
                    </div>
                    <div class="col-md-2">
                        <label>Entrenador ID:</label>
                        <input type="number" name="entrenador_id" class="form-control" value="{{ session('filtros.entrenador_id') }}">
                    </div>
                    <div class="col-md-2">
                        <label>Centro:</label>
                        <input type="text" name="centro" class="form-control" value="{{ session('filtros.centro') }}">
                    </div>
                    <div class="col-md-2">
                        <label>Sala:</label>
                        <input type="text" name="sala" class="form-control" value="{{ session('filtros.sala') }}">
                    </div>
                    <div class="col-md-2">
                        <label>Estado:</label>
                        <select name="estado" class="form-control">
                            <option value="programada" {{ session('filtros.estado', 'programada') == 'programada' ? 'selected' : '' }}>Programada</option>
                            <option value="cancelada" {{ session('filtros.estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            <option value="completada" {{ session('filtros.estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Por página:</label>
                        <select name="per_page" class="form-control">
                            <option value="10" {{ session('filtros.per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ session('filtros.per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ session('filtros.per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>&nbsp;</label><br>
                        <button type="submit" class="btn btn-primary">Buscar</button>
                        <a href="{{ route('sesiones.clear') }}" class="btn btn-secondary">Limpiar</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de sesiones -->
        <div class="card">
            <div class="card-header">
                <h5>Lista de Sesiones ({{ $sesiones->total() }} total)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Clase</th>
                                <th>Entrenador</th>
                                <th>Fecha/Hora</th>
                                <th>Centro</th>
                                <th>Sala</th>
                                <th>Ocupación</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sesiones as $sesion)
                            <tr>
                                <td>{{ $sesion->id }}</td>
                                <td>
                                    <strong>{{ $sesion->clase->nombre }}</strong><br>
                                    <small>{{ $sesion->clase->duracion_min }} min - {{ $sesion->clase->intensidad }}</small>
                                </td>
                                <td>
                                    {{ $sesion->entrenador->nombre }} {{ $sesion->entrenador->apellidos }}<br>
                                    <small>{{ $sesion->entrenador->email }}</small>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($sesion->fecha_inicio)->format('d/m/Y') }}<br>
                                    <strong>{{ \Carbon\Carbon::parse($sesion->fecha_inicio)->format('H:i') }}</strong>
                                </td>
                                <td>{{ $sesion->centro }}</td>
                                <td>{{ $sesion->sala }}</td>
                                <td>
                                    {{ $sesion->ocupacion }}/{{ $sesion->cupo_max }}<br>
                                    <small>
                                        @php
                                            $porcentaje = $sesion->cupo_max > 0 ? ($sesion->ocupacion / $sesion->cupo_max) * 100 : 0;
                                        @endphp
                                        {{ number_format($porcentaje, 0) }}%
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $sesion->estado == 'programada' ? 'success' : ($sesion->estado == 'cancelada' ? 'danger' : 'primary') }}">
                                        {{ ucfirst($sesion->estado) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginación -->
                <div class="d-flex justify-content-center">
                    {{ $sesiones->links() }}
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('demo.bienvenida') }}" class="btn-secondary">← Volver a Bienvenida</a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>