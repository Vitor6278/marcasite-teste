@extends('layouts.guest')

@section('content')

<div class="card shadow-sm">
    <div class="card-header bg-light">{{ __('Login') }}</div>

    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success mb-4" role="alert">
                {{ session('status') }}
            </div>
        @endif

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

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('E-mail') }}</label>
                <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
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
                                required autocomplete="current-password" />
                 @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label">{{ __('Lembrar-me') }}</label>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">

                @if (Route::has('password.request'))
                    <a class="btn btn-link text-decoration-none ps-0" href="{{ route('password.request') }}">
                        {{ __('Esqueceu sua senha?') }}
                    </a>
                @else
                    <span></span> 
                @endif

                <button type="submit" class="btn btn-primary">
                    {{ __('Entrar') }}
                </button>
            </div>

             @if (Route::has('register'))
                <div class="text-center mt-3 pt-3 border-top">
                    <a href="{{ route('register') }}" class="text-decoration-none">Ainda não tem conta? {{ __('Registre-se') }}</a>
                </div>
            @endif

             <div class="text-center mt-2">
                 <a href="{{ route('home') }}" class="btn btn-link btn-sm text-secondary text-decoration-none">Voltar para Home</a>
             </div>
        </form>
    </div>
</div>
@endsection
