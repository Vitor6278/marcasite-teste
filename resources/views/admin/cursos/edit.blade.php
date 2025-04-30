@extends('layouts.app') 

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark"> 
                     {{-- Título dinâmico com o nome do curso --}}
                    <h4 class="mb-0">Editar Curso: {{ $curso->nome }}</h4>
                </div>

                <div class="card-body">
                    {{-- Exibir Erros de Validação --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Formulário de Edição --}}
                    <form method="POST" action="{{ route('admin.cursos.update', $curso->id) }}" enctype="multipart/form-data">
                        @csrf {{-- Token de segurança --}}
                        @method('PUT') 

                        {{-- Nome do Curso --}}
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Curso <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome', $curso->nome) }}" required>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Descrição --}}
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" rows="3" required>{{ old('descricao', $curso->descricao) }}</textarea>
                             @error('descricao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Valor --}}
                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor (R$) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('valor') is-invalid @enderror" id="valor" name="valor" value="{{ old('valor', $curso->valor) }}" required step="0.01" min="0">
                             @error('valor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Data de Início das Inscrições --}}
                        <div class="mb-3">
                            <label for="data_inicio" class="form-label">Data de Início das Inscrições <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('data_inicio') is-invalid @enderror" id="data_inicio" name="data_inicio" value="{{ old('data_inicio', $curso->data_inicio) }}" required>
                             @error('data_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Data de Fim das Inscrições --}}
                        <div class="mb-3">
                            <label for="data_fim" class="form-label">Data de Fim das Inscrições <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('data_fim') is-invalid @enderror" id="data_fim" name="data_fim" value="{{ old('data_fim', $curso->data_fim) }}" required>
                             @error('data_fim')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Quantidade Máxima de Inscritos --}}
                        <div class="mb-3">
                            <label for="quantidade_maxima_inscritos" class="form-label">Quantidade Máxima de Inscritos <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('quantidade_maxima_inscritos') is-invalid @enderror" id="quantidade_maxima_inscritos" name="quantidade_maxima_inscritos" value="{{ old('quantidade_maxima_inscritos', $curso->quantidade_maxima_inscritos) }}" required min="1">
                             @error('quantidade_maxima_inscritos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Upload de Arquivo com Material --}}
                        <div class="mb-3">
                            <label for="arquivo_material" class="form-label">Substituir Arquivo com Material (Opcional - PDF, ZIP, DOC, DOCX - Máx 10MB)</label>
                            <input class="form-control @error('arquivo_material') is-invalid @enderror" type="file" id="arquivo_material" name="arquivo_material">
                            @error('arquivo_material')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            {{-- Mostra o arquivo atual, se existir --}}
                            @if($curso->arquivo_material)
                                <div class="mt-2">
                                    Arquivo Atual: <a href="{{ Storage::url('public/' . $curso->arquivo_material) }}" target="_blank">{{ basename($curso->arquivo_material) }}</a>
                                </div>
                            @endif
                        </div>

                        {{-- Botão Atualizar --}}
                        <div class="d-grid gap-2">
                           <button type="submit" class="btn btn-warning">Atualizar Curso</button> 
                        </div>

                        <div class="mt-3 text-center">
                             <a href="{{ route('admin.cursos.index') }}" class="btn btn-secondary btn-sm">Voltar para a Lista</a>
                        </div>

                    </form>
                </div> 
            </div> 
        </div> 
    </div> 
</div> 
@endsection