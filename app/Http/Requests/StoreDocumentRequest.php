<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
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
            'doc' => ['required', 'mimes:docx,png,bmp,jpg,pdf'],
        ];
    }

    public function messages()
    {
        return [
            'doc.mimes' => ':attribute может быть только форматов .docx, .bmp, .png, .jpg, .pdf',
            'doc.size' => ':attribute не может превышать 10MB'
        ];
    }

    public function attributes()
    {
        return [
            'doc' => 'Загружаемый файл'
        ];
    }
}
