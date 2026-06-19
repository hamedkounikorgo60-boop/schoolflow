<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MatiereRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $matiereId = $this->route('matiere')?->id;

        return [
            'nom'         => 'required|string|max:255|unique:matieres,nom' . ($matiereId ? ",{$matiereId}" : ''),
            'coefficient' => 'required|integer|min:1',
            'niveau'      => 'required|string|max:100',
            'filiere'     => 'required|string|max:100',
        ];
    }
}
