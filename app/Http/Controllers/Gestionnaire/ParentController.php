<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParentController extends Controller
{
    public function index()
    {
        $parents = User::where('role', 'parent')
            ->with('eleves.classe')
            ->orderBy('name')
            ->get();

        return view('gestionnaire.parents.index', compact('parents'));
    }

    public function create()
    {
        $eleves = Eleve::whereNull('parent_id')
            ->with('classe')
            ->orderBy('nom')
            ->get();

        return view('gestionnaire.parents.create', compact('eleves'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email',
            'password'      => 'required|string|min:8|confirmed',
            'telephone'     => 'nullable|string|max:30',
            'adresse'       => 'nullable|string|max:255',
            'eleve_ids'     => 'nullable|array',
            'eleve_ids.*'   => 'exists:eleves,id',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => $request->password,
                'role'      => 'parent',
                'telephone' => $request->telephone,
                'adresse'   => $request->adresse,
            ]);

            if ($request->filled('eleve_ids')) {
                Eleve::whereIn('id', $request->eleve_ids)
                    ->update(['parent_id' => $user->id]);
            }
        });

        return redirect()
            ->route('gestionnaire.parents.index')
            ->with('success', "Parent \u{00AB} {$request->name} \u{00BB} cr\u{00E9}\u{00E9}. Il peut se connecter avec son e-mail sur l'application mobile.");
    }

    public function show(User $user)
    {
        abort_unless($user->role === 'parent', 404);

        $user->load('eleves.classe', 'eleves.notes', 'eleves.paiements');

        return view('gestionnaire.parents.show', compact('user'));
    }

    public function edit(User $user)
    {
        abort_unless($user->role === 'parent', 404);

        $assignedEleves = $user->eleves()->pluck('id')->all();

        $eleves = Eleve::where(function ($query) use ($user) {
                $query->whereNull('parent_id')
                      ->orWhere('parent_id', $user->id);
            })
            ->with('classe')
            ->orderBy('nom')
            ->get();

        return view('gestionnaire.parents.edit', compact('user', 'eleves', 'assignedEleves'));
    }

    public function update(Request $request, User $user)
    {
        abort_unless($user->role === 'parent', 404);

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email,' . $user->id,
            'password'      => 'nullable|string|min:8|confirmed',
            'telephone'     => 'nullable|string|max:30',
            'adresse'       => 'nullable|string|max:255',
            'eleve_ids'     => 'nullable|array',
            'eleve_ids.*'   => 'exists:eleves,id',
        ]);

        DB::transaction(function () use ($request, $user) {
            $data = [
                'name'      => $request->name,
                'email'     => $request->email,
                'telephone' => $request->telephone,
                'adresse'   => $request->adresse,
            ];

            if ($request->filled('password')) {
                $data['password'] = $request->password;
            }

            $user->update($data);

            Eleve::where('parent_id', $user->id)->update(['parent_id' => null]);

            if ($request->filled('eleve_ids')) {
                Eleve::whereIn('id', $request->eleve_ids)
                    ->update(['parent_id' => $user->id]);
            }
        });

        return redirect()
            ->route('gestionnaire.parents.index')
            ->with('success', "Parent \u{00AB} {$user->name} \u{00BB} mis \u{00E0} jour.");
    }

    public function destroy(User $user)
    {
        abort_unless($user->role === 'parent', 404);

        DB::transaction(function () use ($user) {
            Eleve::where('parent_id', $user->id)->update(['parent_id' => null]);
            $user->delete();
        });

        return redirect()
            ->route('gestionnaire.parents.index')
            ->with('success', "Parent supprim\u{00E9} avec succ\u{00E8}s.");
    }
}
