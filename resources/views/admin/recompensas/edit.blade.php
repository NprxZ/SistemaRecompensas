@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-warning">
                    <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Editar Recompensa</h4>
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

                    <form action="{{ route('admin.recompensas.update', $reward->reward_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Título *</label>
                                <input type="text" name="title" class="form-control" 
                                       value="{{ old('title', $reward->title) }}" required maxlength="100">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Puntos Requeridos *</label>
                                <input type="number" name="points_required" class="form-control" 
                                       value="{{ old('points_required', $reward->points_required) }}" required min="1">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción *</label>
                            <textarea name="description" class="form-control" rows="4" required>{{ old('description', $reward->description) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Categoría *</label>
                                <select name="category" class="form-select" required>
                                    <option value="">Selecciona una categoría</option>
                                    <option value="descuentos" {{ old('category', $reward->category) == 'descuentos' ? 'selected' : '' }}>Descuentos</option>
                                    <option value="productos" {{ old('category', $reward->category) == 'productos' ? 'selected' : '' }}>Productos</option>
                                    <option value="servicios" {{ old('category', $reward->category) == 'servicios' ? 'selected' : '' }}>Servicios</option>
                                    <option value="gasolina" {{ old('category', $reward->category) == 'gasolina' ? 'selected' : '' }}>Gasolina</option>
                                    <option value="mantenimiento" {{ old('category', $reward->category) == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                                    <option value="otros" {{ old('category', $reward->category) == 'otros' ? 'selected' : '' }}>Otros</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stock *</label>
                                <input type="number" name="stock" class="form-control" 
                                       value="{{ old('stock', $reward->stock) }}" required min="0">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Imagen</label>
                                @if($reward->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $reward->image) }}" 
                                             alt="{{ $reward->title }}" 
                                             style="max-width: 200px; border-radius: 5px;">
                                    </div>
                                @endif
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <small class="text-muted">Dejar vacío para mantener imagen actual</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha de Expiración</label>
                                <input type="date" name="expiration_date" class="form-control" 
                                       value="{{ old('expiration_date', $reward->expiration_date) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Términos y Condiciones</label>
                            <textarea name="terms_conditions" class="form-control" rows="3">{{ old('terms_conditions', $reward->terms_conditions) }}</textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.recompensas.show', $reward->reward_id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Actualizar Recompensa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection