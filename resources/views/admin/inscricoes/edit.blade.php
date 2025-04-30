@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">Editar Inscrição #{{ $inscricao->id }} - {{ $inscricao->nome_aluno }}</h4>
                    <small>Curso: {{ $inscricao->curso->nome ?? 'N/A' }}</small>
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

                    <form method="POST" action="{{ route('admin.inscricoes.update', $inscricao->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- Linha para Nome e Email --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nome_aluno" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nome_aluno') is-invalid @enderror" id="nome_aluno" name="nome_aluno" value="{{ old('nome_aluno', $inscricao->nome_aluno) }}" required>
                                @error('nome_aluno')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email_aluno" class="form-label">E-mail <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email_aluno') is-invalid @enderror" id="email_aluno" name="email_aluno" value="{{ old('email_aluno', $inscricao->email_aluno) }}" required>
                                @error('email_aluno')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                         {{-- Linha para CPF e Celular --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('cpf') is-invalid @enderror" id="cpf" name="cpf" value="{{ old('cpf', $inscricao->cpf) }}" required placeholder="000.000.000-00">
                                @error('cpf')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                             
                            <div class="col-md-6">
                                <label for="celular" class="form-label">Celular <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('celular') is-invalid @enderror" id="celular" name="celular" value="{{ old('celular', $inscricao->celular) }}" required placeholder="(XX) XXXXX-XXXX">
                                @error('celular')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Endereço --}}
                        <div class="mb-3">
                            <label for="endereco" class="form-label">Endereço Completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('endereco') is-invalid @enderror" id="endereco" name="endereco" value="{{ old('endereco', $inscricao->endereco) }}" required placeholder="Rua, Número, Bairro, Cidade - UF">
                            @error('endereco')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="empresa" class="form-label">Empresa (Opcional)</label>
                                <input type="text" class="form-control @error('empresa') is-invalid @enderror" id="empresa" name="empresa" value="{{ old('empresa', $inscricao->empresa) }}">
                                @error('empresa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                             <div class="col-md-6">
                                <label for="telefone_aluno" class="form-label">Telefone Fixo (Opcional)</label>
                                 {{-- Adicionado ID --}}
                                <input type="text" class="form-control @error('telefone_aluno') is-invalid @enderror" id="telefone_aluno" name="telefone_aluno" value="{{ old('telefone_aluno', $inscricao->telefone_aluno) }}" placeholder="(XX) XXXX-XXXX">
                                @error('telefone_aluno')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                         {{-- Linha para Categoria e Status --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="categoria" class="form-label">Categoria <span class="text-danger">*</span></label>
                                <select class="form-select @error('categoria') is-invalid @enderror" id="categoria" name="categoria" required>
                                    <option value="" disabled>Selecione...</option>
                                    <option value="Estudante" {{ old('categoria', $inscricao->categoria) == 'Estudante' ? 'selected' : '' }}>Estudante</option>
                                    <option value="Profissional" {{ old('categoria', $inscricao->categoria) == 'Profissional' ? 'selected' : '' }}>Profissional</option>
                                    <option value="Associado" {{ old('categoria', $inscricao->categoria) == 'Associado' ? 'selected' : '' }}>Associado</option>
                                </select>
                                @error('categoria')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                             <div class="col-md-6">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="" disabled>Selecione...</option>
                                    <option value="Pendente" {{ old('status', $inscricao->status) == 'Pendente' ? 'selected' : '' }}>Pendente</option>
                                    <option value="Pago" {{ old('status', $inscricao->status) == 'Pago' ? 'selected' : '' }}>Pago</option>
                                    <option value="Cancelado" {{ old('status', $inscricao->status) == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Botão Atualizar Inscrição --}}
                        <div class="d-grid gap-2 mt-4">
                           <button type="submit" class="btn btn-warning btn-lg">Atualizar Inscrição</button>
                        </div>

                         {{-- Botão Voltar --}}
                        <div class="mt-3 text-center">
                             <a href="{{ route('admin.inscricoes.index') }}" class="btn btn-secondary btn-sm">Voltar para a Lista</a>
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
