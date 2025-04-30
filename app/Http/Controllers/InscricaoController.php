<?php

namespace App\Http\Controllers;

use App\Models\Inscricao;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Carbon\Carbon;

class InscricaoController extends Controller
{
    public function create(Curso $curso)
    {
        return view('inscricoes.create', compact('curso'));
    }

    public function store(Request $request, Curso $curso)
    {
        $validatedData = $request->validate([
            'nome_aluno' => 'required|string|max:255',
            'email_aluno' => 'required|email|max:255',
            'cpf' => 'required|string|max:14',
            'endereco' => 'required|string|max:255',
            'celular' => 'required|string|max:20',
            'categoria' => ['required', 'string', Rule::in(['Estudante', 'Profissional', 'Associado'])],
            'empresa' => 'nullable|string|max:255',
            'telefone_aluno' => 'nullable|string|max:20',
        ]);

        $userId = Auth::id();
        if (!$userId) {
             return redirect()->route('login')->with('error', 'Você precisa estar logado para se inscrever.');
        }
        $cursoId = $curso->id;

        $jaInscrito = Inscricao::where('user_id', $userId)
                               ->where('curso_id', $cursoId)
                               ->exists();

        if ($jaInscrito) {
            return redirect()->back()
                             ->with('error', 'Você já está inscrito neste curso.')
                             ->withInput();
        }

        try {
            $validatedData['curso_id'] = $cursoId;
            $validatedData['status'] = 'Pendente';
            $validatedData['user_id'] = $userId;

            Inscricao::create($validatedData);

            return redirect()->route('home')->with('success', 'Inscrição no curso "'.$curso->nome.'" realizada com sucesso! Aguarde confirmação ou prossiga para pagamento.');

        } catch (\Exception $e) {
            Log::error("Erro ao salvar inscrição para curso ID {$curso->id}: " . $e->getMessage());
            return back()->with('error', 'Erro ao realizar inscrição. Verifique os dados e tente novamente.')->withInput();
        }
    }

    public function adminIndex(Request $request)
    {
        $query = Inscricao::with(['curso', 'user']);

        if ($request->filled('busca')) {
            $busca = $request->input('busca');
            $query->where(function ($q) use ($busca) {
                $q->where('nome_aluno', 'like', "%{$busca}%")
                  ->orWhere('email_aluno', 'like', "%{$busca}%")
                  ->orWhere('cpf', 'like', "%{$busca}%");
            });
        }

        if ($request->filled('status') && in_array($request->input('status'), ['Pendente', 'Pago', 'Cancelado'])) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('categoria') && in_array($request->input('categoria'), ['Estudante', 'Profissional', 'Associado'])) {
            $query->where('categoria', $request->input('categoria'));
        }

        if ($request->filled('data_de')) {
            try {
                $dataDe = Carbon::parse($request->input('data_de'))->startOfDay();
                $query->where('created_at', '>=', $dataDe);
            } catch (\Exception $e) {
                Log::warning("Data 'De' inválida recebida no filtro: " . $request->input('data_de'));
            }
        }

        if ($request->filled('data_ate')) {
             try {
                $dataAte = Carbon::parse($request->input('data_ate'))->endOfDay();
                $query->where('created_at', '<=', $dataAte);
            } catch (\Exception $e) {
                Log::warning("Data 'Até' inválida recebida no filtro: " . $request->input('data_ate'));
            }
        }

        $inscricoes = $query->orderBy('created_at', 'desc')
                            ->paginate(15)
                            ->withQueryString();

        return view('admin.inscricoes.index', compact('inscricoes'));
    }

    public function exportPdf(Request $request)
    {
        try {
            $query = Inscricao::with(['curso', 'user']);

            if ($request->filled('busca')) {
                $busca = $request->input('busca');
                $query->where(function ($q) use ($busca) {
                    $q->where('nome_aluno', 'like', "%{$busca}%")
                      ->orWhere('email_aluno', 'like', "%{$busca}%")
                      ->orWhere('cpf', 'like', "%{$busca}%");
                });
            }
            if ($request->filled('status') && in_array($request->input('status'), ['Pendente', 'Pago', 'Cancelado'])) {
                $query->where('status', $request->input('status'));
            }
             if ($request->filled('categoria') && in_array($request->input('categoria'), ['Estudante', 'Profissional', 'Associado'])) {
                $query->where('categoria', $request->input('categoria'));
            }
             if ($request->filled('data_de')) {
                  try { $dataDe = Carbon::parse($request->input('data_de'))->startOfDay(); $query->where('created_at', '>=', $dataDe); } catch (\Exception $e) {}
             }
             if ($request->filled('data_ate')) {
                  try { $dataAte = Carbon::parse($request->input('data_ate'))->endOfDay(); $query->where('created_at', '<=', $dataAte); } catch (\Exception $e) {}
             }

            $inscricoes = $query->orderBy('created_at', 'desc')->get();
            $nomeArquivo = 'inscricoes-' . date('Y-m-d') . '.pdf';
            $pdf = Pdf::loadView('admin.inscricoes.pdf', compact('inscricoes'));
            $pdf->setPaper('a4', 'landscape');
            return $pdf->download($nomeArquivo);

        } catch (\Exception $e) {
            Log::error("Erro ao gerar PDF de inscrições: " . $e->getMessage());
            return redirect()->route('admin.inscricoes.index')->with('error', 'Erro ao gerar o arquivo PDF.');
        }
    }

    public function exportExcel(Request $request)
    {
        try {
             $query = Inscricao::with(['curso', 'user']);
             if ($request->filled('busca')) {
                  $busca = $request->input('busca');
                  $query->where(function ($q) use ($busca) {
                       $q->where('nome_aluno', 'like', "%{$busca}%")
                         ->orWhere('email_aluno', 'like', "%{$busca}%")
                         ->orWhere('cpf', 'like', "%{$busca}%");
                  });
             }
             if ($request->filled('status') && in_array($request->input('status'), ['Pendente', 'Pago', 'Cancelado'])) {
                  $query->where('status', $request->input('status'));
             }
             if ($request->filled('categoria') && in_array($request->input('categoria'), ['Estudante', 'Profissional', 'Associado'])) {
                  $query->where('categoria', $request->input('categoria'));
             }
             if ($request->filled('data_de')) {
                  try { $dataDe = Carbon::parse($request->input('data_de'))->startOfDay(); $query->where('created_at', '>=', $dataDe); } catch (\Exception $e) {}
             }
             if ($request->filled('data_ate')) {
                   try { $dataAte = Carbon::parse($request->input('data_ate'))->endOfDay(); $query->where('created_at', '<=', $dataAte); } catch (\Exception $e) {}
             }
             $inscricoes = $query->orderBy('created_at', 'desc')->get();

             $spreadsheet = new Spreadsheet();
             $sheet = $spreadsheet->getActiveSheet();
             $sheet->setTitle('Inscrições');
             $headings = [
                  'ID', 'Data Inscrição', 'Aluno', 'E-mail', 'CPF', 'Celular',
                  'Endereço', 'Empresa', 'Telefone Fixo', 'Categoria', 'Status',
                  'Curso ID', 'Nome Curso', 'ID Usuário', 'Nome Usuário'
             ];
             $coluna = 'A';
             foreach ($headings as $heading) {
                  $sheet->setCellValue($coluna.'1', $heading);
                  $sheet->getColumnDimension($coluna)->setAutoSize(true);
                  $coluna++;
             }
              $sheet->getStyle('A1:'.(--$coluna).'1')->getFont()->setBold(true);

             $linha = 2;
             foreach ($inscricoes as $inscricao) {
                  $sheet->setCellValue('A'.$linha, $inscricao->id);
                  $sheet->setCellValue('B'.$linha, $inscricao->created_at->format('d/m/Y H:i:s'));
                  $sheet->setCellValue('C'.$linha, $inscricao->nome_aluno);
                  $sheet->setCellValue('D'.$linha, $inscricao->email_aluno);
                  $sheet->setCellValue('E'.$linha, $inscricao->cpf);
                  $sheet->setCellValue('F'.$linha, $inscricao->celular);
                  $sheet->setCellValue('G'.$linha, $inscricao->endereco);
                  $sheet->setCellValue('H'.$linha, $inscricao->empresa);
                  $sheet->setCellValue('I'.$linha, $inscricao->telefone_aluno);
                  $sheet->setCellValue('J'.$linha, $inscricao->categoria);
                  $sheet->setCellValue('K'.$linha, $inscricao->status);
                  $sheet->setCellValue('L'.$linha, $inscricao->curso_id);
                  $sheet->setCellValue('M'.$linha, $inscricao->curso->nome ?? 'N/A');
                  $sheet->setCellValue('N'.$linha, $inscricao->user_id);
                  $sheet->setCellValue('O'.$linha, $inscricao->user->name ?? 'N/A');
                  $linha++;
             }

             $nomeArquivo = 'inscricoes-' . date('Y-m-d_H-i') . '.xlsx';
             $writer = new Xlsx($spreadsheet);

             $response = new StreamedResponse(function() use ($writer) {
                  $writer->save('php://output');
             });

             $disposition = $response->headers->makeDisposition(
                  ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                  $nomeArquivo
             );

             $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
             $response->headers->set('Content-Disposition', $disposition);
             $response->headers->set('Cache-Control', 'max-age=0');

             return $response;

        } catch (\Exception $e) {
            Log::error("Erro ao gerar Excel de inscrições (Direto): " . $e->getMessage());
            return redirect()->route('admin.inscricoes.index')->with('error', 'Erro ao gerar o arquivo Excel.');
        }
    }

    public function edit(Inscricao $inscricao)
    {
        $inscricao->load('curso');
        return view('admin.inscricoes.edit', compact('inscricao'));
    }

    public function update(Request $request, Inscricao $inscricao)
    {
        $validatedData = $request->validate([
            'nome_aluno' => 'required|string|max:255',
            'email_aluno' => 'required|email|max:255',
            'cpf' => 'required|string|max:14',
            'endereco' => 'required|string|max:255',
            'celular' => 'required|string|max:20',
            'categoria' => ['required', 'string', Rule::in(['Estudante', 'Profissional', 'Associado'])],
            'empresa' => 'nullable|string|max:255',
            'telefone_aluno' => 'nullable|string|max:20',
            'status' => ['required', 'string', Rule::in(['Pendente', 'Pago', 'Cancelado'])],
        ]);

        try {
            $inscricao->update($validatedData);
            return redirect()->route('admin.inscricoes.index')->with('success', 'Inscrição atualizada com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar inscrição ID {$inscricao->id}: " . $e->getMessage());
            return back()->with('error', 'Erro ao atualizar inscrição. Tente novamente.')->withInput();
        }
    }

    public function destroy(Inscricao $inscricao)
    {
        try {
            $nomeAluno = $inscricao->nome_aluno;
            $inscricao->delete();
            return redirect()->route('admin.inscricoes.index')->with('success', 'Inscrição de '.$nomeAluno.' excluída com sucesso!');
        } catch (\Exception $e) {
             Log::error("Erro ao excluir inscrição ID {$inscricao->id}: " . $e->getMessage());
            return redirect()->route('admin.inscricoes.index')->with('error', 'Erro ao excluir inscrição.');
        }
    }
}