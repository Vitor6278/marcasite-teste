@extends('layouts.app') {{-- Usa o layout principal --}}

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Gerenciar Cursos</h2>
        </div>
        <div class="col-md-6 text-end">
            {{-- Botão para adicionar novo curso --}}
            <a href="{{ route('admin.cursos.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Adicionar Novo Curso
            </a>
        </div>
    </div>

     @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Valor</th>
                        <th>Início Inscr.</th>
                        <th>Fim Inscr.</th>
                        <th>Qtd. Max.</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cursos as $curso)
                        <tr>
                            <td>{{ $curso->id }}</td>
                            <td>{{ $curso->nome }}</td>
                            <td>R$ {{ number_format($curso->valor, 2, ',', '.') }}</td>
                            <td>{{ \Carbon\Carbon::parse($curso->data_inicio)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($curso->data_fim)->format('d/m/Y') }}</td>
                            <td>{{ $curso->quantidade_maxima_inscritos }}</td>
                            <td>
                                {{-- Botão Editar --}}
                                <a href="{{ route('admin.cursos.edit', $curso->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i> Editar
                                </a>

                                {{-- Botão Excluir (com formulário) --}}
                                <form action="{{ route('admin.cursos.destroy', $curso->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir o curso \'{{ $curso->nome }}\'? Isso não poderá ser desfeito!');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Excluir">
                                        <i class="fas fa-trash"></i> Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Nenhum curso cadastrado ainda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {{ $cursos->links() }}
            </div>

        </div> 
    </div> 
</div> 

