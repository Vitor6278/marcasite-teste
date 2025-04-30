<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Inscricao; // Added for enrollment check
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // Added for Auth check
use Symfony\Component\HttpFoundation\Response; // Added for abort

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::where('data_inicio', '<=', now())
                       ->where('data_fim', '>=', now())
                       ->orderBy('nome')
                       ->paginate(10);
        return view('cursos.index', compact('cursos'));
    }

    public function adminIndex()
    {
        $cursos = Curso::orderBy('nome', 'asc')->paginate(15);
        return view('admin.cursos.index', compact('cursos'));
    }

    public function create()
    {
        return view('admin.cursos.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255|unique:cursos,nome',
            'descricao' => 'required|string',
            'valor' => 'required|numeric|min:0',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'quantidade_maxima_inscritos' => 'required|integer|min:1',
            'arquivo_material' => 'nullable|file|mimes:pdf,zip,doc,docx|max:10240',
        ]);

        try {
            $filePath = null;
            if ($request->hasFile('arquivo_material') && $request->file('arquivo_material')->isValid()) {
                $nomeCursoSlug = \Illuminate\Support\Str::slug($validatedData['nome']);
                $nomeArquivo = $nomeCursoSlug . '_' . time() . '.' . $request->file('arquivo_material')->extension();
                $path = $request->file('arquivo_material')->storeAs('public/materiais', $nomeArquivo);
                $filePath = str_replace('public/', '', $path);
            }
            $validatedData['arquivo_material'] = $filePath;

            $curso = Curso::create($validatedData);

            return redirect()->route('admin.cursos.index')->with('success', 'Curso "'.$curso->nome.'" criado com sucesso!');

        } catch (\Exception $e) {
            Log::error("Erro ao criar curso: " . $e->getMessage() . "\nStack trace:\n" . $e->getTraceAsString());
            return back()->with('error', 'Erro ao salvar o curso. Por favor, tente novamente.')->withInput();
        }
    }

    public function edit(Curso $curso)
    {
        return view('admin.cursos.edit', compact('curso'));
    }

    public function update(Request $request, Curso $curso)
    {
        $validatedData = $request->validate([
            'nome' => ['required','string','max:255', Rule::unique('cursos')->ignore($curso->id)],
            'descricao' => 'required|string',
            'valor' => 'required|numeric|min:0',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'quantidade_maxima_inscritos' => 'required|integer|min:1',
            'arquivo_material' => 'nullable|file|mimes:pdf,zip,doc,docx|max:10240',
        ]);

        try {
            $filePath = $curso->arquivo_material;

            if ($request->hasFile('arquivo_material') && $request->file('arquivo_material')->isValid()) {
                if ($curso->arquivo_material) {
                    Storage::delete('public/' . $curso->arquivo_material);
                }
                $nomeCursoSlug = \Illuminate\Support\Str::slug($validatedData['nome']);
                $nomeArquivo = $nomeCursoSlug . '_' . time() . '.' . $request->file('arquivo_material')->extension();
                $path = $request->file('arquivo_material')->storeAs('public/materiais', $nomeArquivo);
                $filePath = str_replace('public/', '', $path);
            }
            $validatedData['arquivo_material'] = $filePath;

            $curso->update($validatedData);

            return redirect()->route('admin.cursos.index')->with('success', 'Curso "'.$curso->nome.'" atualizado com sucesso!');

        } catch (\Exception $e) {
            Log::error("Erro ao atualizar curso ID {$curso->id}: " . $e->getMessage() . "\nStack trace:\n" . $e->getTraceAsString());
            return back()->with('error', 'Erro ao atualizar o curso.')->withInput();
        }
    }

    public function destroy(Curso $curso)
    {
        try {
            $nomeCurso = $curso->nome;
            $arquivoPath = $curso->arquivo_material;

            $curso->delete();

            if ($arquivoPath) {
                Storage::delete('public/' . $arquivoPath);
            }

            return redirect()->route('admin.cursos.index')->with('success', 'Curso "'.$nomeCurso.'" excluído com sucesso!');

        } catch (\Exception $e) {
            Log::error("Erro ao excluir curso ID {$curso->id}: " . $e->getMessage() . "\nStack trace:\n" . $e->getTraceAsString());
            return redirect()->route('admin.cursos.index')->with('error', 'Erro ao excluir o curso.');
        }
    }

    public function downloadMaterial(Curso $curso)
    {
        if (!Auth::check()) {
            abort(Response::HTTP_FORBIDDEN, 'Acesso negado. Faça login para continuar.');
        }

        $user = Auth::user();

        $isEnrolled = Inscricao::where('user_id', $user->id)
                               ->where('curso_id', $curso->id)
                               ->exists();

        if (!($user->tipo_usuario == 'Admin' || $isEnrolled)) {
            abort(Response::HTTP_FORBIDDEN, 'Acesso negado. Você não tem permissão para baixar este material.');
        }

        if (!$curso->arquivo_material) {
            abort(Response::HTTP_NOT_FOUND, 'Material não encontrado para este curso.');
        }

        $path = 'public/' . $curso->arquivo_material;

        if (!Storage::exists($path)) {
            Log::error("Arquivo de material não encontrado no storage para o curso ID {$curso->id} no caminho: {$path}");
            abort(Response::HTTP_NOT_FOUND, 'Arquivo de material não encontrado.');
        }

        try {
            $nomeOriginal = basename($curso->arquivo_material); 
            $nomeLimpo = preg_replace('/^[\w-]+_\d+_/', '', $nomeOriginal);

            return Storage::download($path, $nomeLimpo ?: $nomeOriginal);

        } catch (\Exception $e) {
            Log::error("Erro ao tentar baixar o arquivo {$path} para o curso ID {$curso->id}: " . $e->getMessage());
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Erro ao processar o download do arquivo.');
        }
    }
}
