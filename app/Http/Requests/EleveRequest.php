<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EleveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $eleveId = $this->route('eleve')?->id;

        $rules = [
            'matricule'      => 'required|unique:eleves,matricule' . ($eleveId ? ",{$eleveId}" : ''),
            'nom'            => 'required|string|max:100',
            'prenoms'        => 'required|string|max:100',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:100',
            'genre'          => 'required|in:M,F',
            'classe_id'      => 'required|exists:classes,id',
            'telephone'      => 'nullable|string|max:20',
            'adresse'        => 'nullable|string|max:255',
            'photo'          => 'nullable|image|max:2048',
            'statut'         => 'required|in:actif,inactif',
        ];

        if (! $eleveId) {
            $rules['redoublant'] = 'boolean';
        }

        return $rules;
    }
}
