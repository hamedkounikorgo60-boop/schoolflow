<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Enseignant;
use App\Models\Matiere;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnseignantController extends Controller
{
    public function index()
    {
        $enseignants = User::where('role', 'enseignant')
            ->with(['enseignant.matieres', 'classes'])
            ->orderBy('name')
            ->get();

        return view('gestionnaire.enseignants.index', compact('enseignants'));
    }

    public function create()
    {
        $classes  = Classe::orderBy('nom')->get();
        $matieres = Matiere::orderBy('nom')->get();

        return view('gestionnaire.enseignants.create', compact('classes', 'matieres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|max:255|unique:users,email',
            'password'        => 'required|string|min:8|confirmed',
            'telephone'       => 'nullable|string|max:30',
            'specialite'      => 'nullable|string|max:255',
            'classe_ids'      => 'nullable|array',
            'classe_ids.*'    => 'exists:classes,id',
            'matiere_ids'     => 'nullable|array',
            'matiere_ids.*'   => 'exists:matieres,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'name'     => $request->name,
                    'email'    => $request->email,
                    'password' => $request->password,
                    'role'     => 'enseignant',
                ]);

                $profil = Enseignant::create([
                    'user_id'    => $user->id,
                    'specialite' => $request->specialite,
                    'telephone'  => $request->telephone,
                ]);

                $user->classes()->sync($request->input('classe_ids', []));
                $profil->matieres()->sync($request->input('matiere_ids', []));
            });
        } catch (\Throwable $e) {
            Log::error('Échec de la création de l\'enseignant', ['error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['general' => 'Erreur lors de la création de l\'enseignant.']);
        }

        return redirect()
            ->route('gestionnaire.enseignants.index')
            ->with('success', "Enseignant « {$request->name} » créé. Il peut se connecter avec son e-mail.");
    }

    public function edit(User $user)
    {
        abort_unless($user->role === 'enseignant', 404);

        $profil = $user->enseignant ?? Enseignant::create([
            'user_id'    => $user->id,
            'specialite' => '',
            'telephone'  => '',
        ]);

        $classes         = Classe::orderBy('nom')->get();
        $matieres        = Matiere::orderBy('nom')->get();
        $assignedClasses = $user->classes()->pluck('classes.id')->all();
        $assignedMatieres = $profil->matieres()->pluck('matieres.id')->all();

        return view('gestionnaire.enseignants.edit', compact(
            'user',
            'profil',
            'classes',
            'matieres',
            'assignedClasses',
            'assignedMatieres'
        ));
    }

    public function update(Request $request, User $user)
    {
        abort_unless($user->role === 'enseignant', 404);

        $request->validate([
            'classe_ids'    => 'nullable|array',
            'classe_ids.*'  => 'exists:classes,id',
            'matiere_ids'   => 'nullable|array',
            'matiere_ids.*' => 'exists:matieres,id',
        ]);

        try {
            $user->classes()->sync($request->input('classe_ids', []));

            $profil = $user->enseignant ?? Enseignant::create([
                'user_id'    => $user->id,
                'specialite' => '',
                'telephone'  => '',
            ]);

            $profil->matieres()->sync($request->input('matiere_ids', []));
        } catch (\Throwable $e) {
            Log::error('Échec de la mise à jour de l\'enseignant', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['general' => 'Erreur lors de la mise à jour des affectations.']);
        }

        return redirect()
            ->route('gestionnaire.enseignants.index')
            ->with('success', "Affectations mises à jour pour {$user->name}.");
    }
}
