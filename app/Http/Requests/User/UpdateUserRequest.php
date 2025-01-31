<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'phone' => [
                'sometimes',
                'string',
                'regex:/^(06|07)[0-9]{8}$/'
            ],
            'birth_date' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'role' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255',

            'grade' => 'nullable|integer',
            'orcid' => 'nullable|string|max:255',
            'function' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'activities' => 'nullable|string',
            'institution' => 'nullable|string',
            'level' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.string' => 'Le prénom doit être une chaîne de caractères.',
            'first_name.max' => 'Le prénom ne doit pas dépasser 255 caractères.',

            'last_name.string' => 'Le nom doit être une chaîne de caractères.',
            'last_name.max' => 'Le nom ne doit pas dépasser 255 caractères.',

            'phone.string' => 'Le numéro de téléphone doit être une chaîne de caractères.',
            'phone.regex' => 'Le numéro de téléphone doit commencer par 06 ou 07 et contenir 10 chiffres.',

            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être au format jpg, jpeg ou png.',
            'image.max' => 'L\'image ne doit pas dépasser 2 Mo.',


            'email.string' => 'L\'adresse e-mail doit être une chaîne de caractères.',
            'email.email' => 'L\'adresse e-mail doit être une adresse valide.',
            'email.max' => 'L\'adresse e-mail ne doit pas dépasser 255 caractères.',
        ];
    }
}
