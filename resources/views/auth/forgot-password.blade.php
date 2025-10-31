@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card cuadro-login">
                <div class="card-header">
                    <h4>Recuperar Contraseña</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif
                    
                    <p>Ingresa tu correo electrónico y te enviaremos tu contraseña.</p>
                    
                    <form action="{{ route('auth.password.email') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="email" class="form-control" 
                                   placeholder="correo@gmail.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Numero de Placa</label>
                            <input type="text" name="plate_number" class="form-control" 
                                   placeholder="AAA-444" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            Enviar Contraseña
                        </button>
                        
                        <div class="text-center">
                            <a href="{{ route('home') }}">Volver al inicio</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection