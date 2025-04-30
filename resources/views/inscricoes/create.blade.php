@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Formulário de Inscrição - {{ $curso->nome }}</h4>
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

                    <form method="POST" action="{{ route('inscricoes.store', $curso->id) }}">
                        @csrf

                        {{-- Nome do Aluno --}}
                        <div class="mb-3">
                            <label for="nome_aluno" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nome_aluno') is-invalid @enderror" id="nome_aluno" name="nome_aluno" value="{{ old('nome_aluno') }}" required>
                            @error('nome_aluno')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email_aluno" class="form-label">E-mail <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email_aluno') is-invalid @enderror" id="email_aluno" name="email_aluno" value="{{ old('email_aluno') }}" required>
                            @error('email_aluno')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- CPF --}}
                        <div class="mb-3">
                            <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('cpf') is-invalid @enderror" id="cpf" name="cpf" value="{{ old('cpf') }}" required placeholder="000.000.000-00">
                            @error('cpf')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Endereço --}}
                        <div class="mb-3">
                            <label for="endereco" class="form-label">Endereço Completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('endereco') is-invalid @enderror" id="endereco" name="endereco" value="{{ old('endereco') }}" required placeholder="Rua, Número, Bairro, Cidade - UF">
                            @error('endereco')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Empresa --}}
                        <div class="mb-3">
                            <label for="empresa" class="form-label">Empresa (Opcional)</label>
                            <input type="text" class="form-control @error('empresa') is-invalid @enderror" id="empresa" name="empresa" value="{{ old('empresa') }}">
                            @error('empresa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Telefone Fixo --}}
                         <div class="mb-3">
                            <label for="telefone_aluno" class="form-label">Telefone Fixo (Opcional)</label>
                            <input type="text" class="form-control @error('telefone_aluno') is-invalid @enderror" id="telefone_aluno" name="telefone_aluno" value="{{ old('telefone_aluno') }}" placeholder="(XX) XXXX-XXXX">
                            @error('telefone_aluno')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Celular --}}
                        <div class="mb-3">
                            <label for="celular" class="form-label">Celular <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('celular') is-invalid @enderror" id="celular" name="celular" value="{{ old('celular') }}" required placeholder="(XX) XXXXX-XXXX">
                            @error('celular')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                         {{-- Categoria --}}
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoria <span class="text-danger">*</span></label>
                            <select class="form-select @error('categoria') is-invalid @enderror" id="categoria" name="categoria" required>
                                <option value="" disabled {{ old('categoria') ? '' : 'selected' }}>Selecione...</option>
                                <option value="Estudante" {{ old('categoria') == 'Estudante' ? 'selected' : '' }}>Estudante</option>
                                <option value="Profissional" {{ old('categoria') == 'Profissional' ? 'selected' : '' }}>Profissional</option>
                                <option value="Associado" {{ old('categoria') == 'Associado' ? 'selected' : '' }}>Associado</option>
                            </select>
                            @error('categoria')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                           <button type="submit" class="btn btn-success btn-lg">Realizar Inscrição</button>
                        </div>

                         <div class="mt-3 text-center">
                             <a href="{{ route('home') }}" class="btn btn-secondary btn-sm">Voltar para Lista de Cursos</a>
                         </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/imask"></script>
<script>
  // Espera o documento carregar
  document.addEventListener('DOMContentLoaded', function () {
    // Aplica máscara de CPF
    var cpfInput = document.getElementById('cpf');
    if (cpfInput) {
      IMask(cpfInput, {
        mask: '000.000.000-00'
      });
    }

    // Aplica máscara de Celular 
    var celularInput = document.getElementById('celular');
    if (celularInput) {
      IMask(celularInput, {
        mask: '(00) 00000-0000'
      });
    }

    // Aplica máscara de Telefone Fixo 
    var telefoneInput = document.getElementById('telefone_aluno');
    if (telefoneInput) {
       IMask(telefoneInput, {
        mask: '(00) 0000-0000'
      });
    }
  });
</script>
@endpush
