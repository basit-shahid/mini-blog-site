<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
        'category_id' => ['required', 'exists:categories,id'],
        'series_id'   => ['nullable', 'exists:series,id'],
        'excerpt'     => ['nullable', 'string'],
        'content'     => ['required', 'string'],
        'status'      => ['required', 'in:draft,published,archived'],
        'tags'        => ['array'],
        'tags.*'      => ['exists:tags,id'],
        ];
    }

    public function authorize():bool{
        return true;
    }

}
