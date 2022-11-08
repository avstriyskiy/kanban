<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => ['required', 'max:255']
        ];
    }

    public function messages()
    {
        return [
            'body.required' => 'Пожалуйста, введите :attribute',
            'body.max' => 'К сожалению, :attribute не может быть больше 255 символов'
        ];
    }

    public function attributes()
    {
        return [
            'body' => 'текст комментария'
        ];
    }
}
