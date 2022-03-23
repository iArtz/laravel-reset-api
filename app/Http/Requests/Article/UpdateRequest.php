<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->route('article')->user->id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'article.title'     => 'sometimes|string|max:255',
            'article.tagList'   => 'sometimes|array',
            'article.taglist.*' => 'sometimes|string|max:255',
        ];
    }
}
