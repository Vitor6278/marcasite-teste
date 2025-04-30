@extends('layouts.guest')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-light">{{ __('Registrar') }}</div>

    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <div class="fw-medium">{{ __('Opa! Algo deu errado.') }}</div>
                <ul class='mt-2 mb-0'>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Nome') }}</label>
                <input id="name" class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
                 @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('E-mail') }}</label>
                <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
                 @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Senha') }}</label>
                <input id="password" class="form-control @error('password') is-invalid @enderror"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
                 @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">{{ __('Confirmar Senha') }}</label>
                <input id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                 @error('password_confirmation')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                 <label for="tipo_usuario" class="form-label">{{ __('Tipo de Usuário') }} <span class="text-danger">*</span></label>
                 <select id="tipo_usuario" name="tipo_usuario" class="form-select @error('tipo_usuario') is-invalid @enderror" required>
                    <option value="" disabled {{ old('tipo_usuario') ? '' : 'selected' }}>Selecione o tipo...</option>
                    <option value="Aluno" {{ old('tipo_usuario') == 'Aluno' ? 'selected' : '' }}>Aluno</option>
                    <option value="Admin" {{ old('tipo_usuario') == 'Admin' ? 'selected' : '' }}>Administrador</option>
                </select>
                @error('tipo_usuario')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end align-items-center mt-4">
                <a class="text-decoration-none me-3" href="{{ route('login') }}">
                    {{ __('Já registrado?') }}
                </a>

                <button type="submit" class="btn btn-primary">
                    {{ __('Registrar') }}
                </button>
            </div>

             <div class="text-center mt-2 pt-2 border-top">
                 <a href="{{ route('home') }}" class="btn btn-link btn-sm text-secondary text-decoration-none">Voltar para Home</a>
             </div>
        </form>
    </div>
</div>
@endsection
