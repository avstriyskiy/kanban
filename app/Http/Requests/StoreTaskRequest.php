<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\File;

class StoreTaskRequest extends FormRequest
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
        $rules = [
            'name' => ['required', 'max:40'],
            'description' => ['required', 'max:255'],
            'deadline' => 'required'
        ];
        if ($this->hasFile('doc')){
            $rules = $rules + ['doc' => ['required', 'mimes:docx,png,bmp,jpg,pdf']];
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле :attribute обязательно для заполнения',
            'name.max' => 'Поле :attribute должно быть меньше 40 символов',
            'description.required' => 'Поле :attribute обязательно для заполнения',
            'description.max' => 'Поле :attribute должно быть меньше 255 символов',
            'deadline.required' => 'Поле :attribute обязательно',
            'doc.mimes' => 'Загружаемый файл может быть только форматов .docx, .bmp, .png, .jpg, .pdf'
        ];
    }

    public function attributes()
    {
        return [
            'name' => '"Название"',
            'description' => '"Описание"',
            'deadline' => '"Контрольный срок выполнения задачи"'
        ];
    }
}
