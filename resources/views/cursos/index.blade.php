@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Cursos Disponíveis</h2>

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

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            Lista de Cursos
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Nome do Curso</th>
                            <th scope="col">Descrição</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Início Inscrições</th>
                            <th scope="col">Fim Inscrições</th>
                            <th scope="col" class="text-center">Material</th>
                            <th scope="col" class="text-center">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cursos as $curso)
                            <tr>
                                <td>{{ $curso->nome }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($curso->descricao, 80) }}</td>
                                <td>R$ {{ number_format($curso->valor, 2, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($curso->data_inicio)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($curso->data_fim)->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    @if($curso->arquivo_material)
                                        <a href="{{ route('cursos.material.download', $curso) }}" class="btn btn-sm btn-info" title="Baixar Material">
                                            <i class="fas fa-download"></i> Baixar
                                        </a>
                                    @else
                                        <span class="text-muted fst-italic">N/D</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('inscricoes.create', $curso->id) }}" class="btn btn-primary btn-sm">
                                        Inscrever-se
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center p-4">Nenhum curso disponível no momento.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
         @if($cursos->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-center">
                    {{ $cursos->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
