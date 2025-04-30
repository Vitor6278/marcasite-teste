<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Lista de Inscrições</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; word-wrap: break-word; } /* Adicionado word-wrap */
        th { background-color: #f2f2f2; font-weight: bold; }
        h1 { text-align: center; margin-bottom: 20px; }
        .page-break { page-break-after: always; }
        .status-pendente { background-color: #fff3cd; }
        .status-pago { background-color: #d1e7dd; }
        .status-cancelado { background-color: #f8d7da; }
    </style>
</head>
<body>
    <h1>Lista de Inscrições - {{ date('d/m/Y') }}</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Aluno</th>
                <th>E-mail</th>
                <th>CPF</th>
                <th>Celular</th>
                <th>Curso</th>
                <th>Categoria</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($inscricoes as $inscricao)
                <tr class="{{ strtolower('status-'.$inscricao->status) }}">
                    <td>{{ $inscricao->id }}</td>
                    <td>{{ $inscricao->created_at->format('d/m/Y') }}</td>
                    <td>{{ $inscricao->nome_aluno }}</td>
                    <td>{{ $inscricao->email_aluno }}</td>
                    <td>{{ $inscricao->cpf }}</td>
                    <td>{{ $inscricao->celular }}</td>
                    <td>{{ $inscricao->curso->nome ?? 'N/A' }}</td>
                    <td>{{ $inscricao->categoria }}</td>
                    <td>{{ $inscricao->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">Nenhuma inscrição encontrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
