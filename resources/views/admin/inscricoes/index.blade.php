@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Gerenciar Inscrições</h2>
        </div>
        {{-- Botões de Ação --}}
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.inscricoes.export.pdf') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
            <a href="{{ route('admin.inscricoes.export.excel') }}" class="btn btn-success btn-sm ms-1">
                <i class="fas fa-file-excel"></i> Exportar XLS
            </a>
        </div>
    </div>

    {{-- Mensagens Flash --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
     @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Formulário de Filtros --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Filtrar Inscrições</h5>
            <form method="GET" action="{{ route('admin.inscricoes.index') }}">
                <div class="row g-3 align-items-end">
                    {{-- Campo de Busca --}}
                    <div class="col-md-3">
                        <label for="busca" class="form-label">Buscar por Aluno/Email/CPF:</label>
                        <input type="text" class="form-control form-control-sm" id="busca" name="busca" value="{{ request('busca') }}">
                    </div>

                    {{-- Filtro por Status --}}
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status:</label>
                        <select class="form-select form-select-sm" id="status" name="status">
                            <option value="">Todos</option>
                            <option value="Pendente" {{ request('status') == 'Pendente' ? 'selected' : '' }}>Pendente</option>
                            <option value="Pago" {{ request('status') == 'Pago' ? 'selected' : '' }}>Pago</option>
                            <option value="Cancelado" {{ request('status') == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>

                    {{-- Filtro por Categoria --}}
                    <div class="col-md-2">
                        <label for="categoria" class="form-label">Categoria:</label>
                        <select class="form-select form-select-sm" id="categoria" name="categoria">
                            <option value="">Todas</option>
                            <option value="Estudante" {{ request('categoria') == 'Estudante' ? 'selected' : '' }}>Estudante</option>
                            <option value="Profissional" {{ request('categoria') == 'Profissional' ? 'selected' : '' }}>Profissional</option>
                            <option value="Associado" {{ request('categoria') == 'Associado' ? 'selected' : '' }}>Associado</option>
                        </select>
                    </div>

                    {{-- Filtro por Período --}}
                    <div class="col-md-2">
                        <label for="data_de" class="form-label">Data De:</label>
                        <input type="date" class="form-control form-control-sm" id="data_de" name="data_de" value="{{ request('data_de') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="data_ate" class="form-label">Data Até:</label>
                        <input type="date" class="form-control form-control-sm" id="data_ate" name="data_ate" value="{{ request('data_ate') }}">
                    </div>


                    {{-- Botões do Formulário --}}
                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
                        <a href="{{ route('admin.inscricoes.index') }}" class="btn btn-secondary btn-sm ms-1">Limpar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- Fim do Formulário de Filtros --}}


    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered align-middle">
                     <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Data Inscrição</th>
                            <th>Aluno</th>
                            <th>E-mail</th>
                            <th>CPF</th>
                            <th>Celular</th>
                            <th>Curso</th>
                            <th>Categoria</th>
                            <th>Status</th>
                            <th style="width: 150px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inscricoes as $inscricao)
                            <tr>
                                <td>{{ $inscricao->id }}</td>
                                <td>{{ $inscricao->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $inscricao->nome_aluno }}</td>
                                <td>{{ $inscricao->email_aluno }}</td>
                                <td>{{ $inscricao->cpf }}</td>
                                <td>{{ $inscricao->celular }}</td>
                                <td>{{ $inscricao->curso->nome ?? 'Curso não encontrado' }}</td>
                                <td>{{ $inscricao->categoria }}</td>
                                <td>
                                    @if($inscricao->status == 'Pendente')
                                        <span class="badge bg-warning text-dark">{{ $inscricao->status }}</span>
                                    @elseif($inscricao->status == 'Pago')
                                        <span class="badge bg-success">{{ $inscricao->status }}</span>
                                    @elseif($inscricao->status == 'Cancelado')
                                        <span class="badge bg-danger">{{ $inscricao->status }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $inscricao->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.inscricoes.edit', $inscricao->id) }}" class="btn btn-warning btn-sm flex-shrink-0" title="Editar">
                                            <i class="fas fa-edit"></i><span class="d-none d-md-inline"> Editar</span>
                                        </a>
                                        <form action="{{ route('admin.inscricoes.destroy', $inscricao->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir a inscrição de {{ $inscricao->nome_aluno }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm flex-shrink-0" title="Excluir">
                                                <i class="fas fa-trash"></i><span class="d-none d-md-inline"> Excluir</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">Nenhuma inscrição encontrada para os filtros aplicados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $inscricoes->appends(request()->query())->links() }}
            </div>

        </div>
    </div>
</div>

@endsection
