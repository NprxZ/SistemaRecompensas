@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-warning">
                    <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Nueva Recompensa</h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.recompensas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Título *</label>
                                <input type="text" name="title" class="form-control" 
                                       value="{{ old('title') }}" required maxlength="100">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Puntos Requeridos *</label>
                                <input type="number" name="points_required" class="form-control" 
                                       value="{{ old('points_required') }}" required min="1">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción *</label>
                            <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Categoría *</label>
                                <select name="category" class="form-select" required>
                                    <option value="">Selecciona una categoría</option>
                                    <option value="descuentos" {{ old('category') == 'descuentos' ? 'selected' : '' }}>Descuentos</option>
                                    <option value="productos" {{ old('category') == 'productos' ? 'selected' : '' }}>Productos</option>
                                    <option value="servicios" {{ old('category') == 'servicios' ? 'selected' : '' }}>Servicios</option>
                                    <option value="gasolina" {{ old('category') == 'gasolina' ? 'selected' : '' }}>Gasolina</option>
                                    <option value="mantenimiento" {{ old('category') == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                                    <option value="otros" {{ old('category') == 'otros' ? 'selected' : '' }}>Otros</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stock Inicial *</label>
                                <input type="number" name="stock" class="form-control" 
                                       value="{{ old('stock', 0) }}" required min="0">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Imagen</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <small class="text-muted">Máximo 2MB. Formatos: JPG, PNG</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha de Expiración</label>
                                <input type="date" name="expiration_date" class="form-control" 
                                       value="{{ old('expiration_date') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Términos y Condiciones</label>
                            <textarea name="terms_conditions" class="form-control" rows="3">{{ old('terms_conditions') }}</textarea>
                            <small class="text-muted">Opcional: Agrega restricciones o condiciones de uso</small>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.recompensas.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Guardar Recompensa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection