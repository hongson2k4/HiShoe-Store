<?php

namespace App\Http\Requests\Request;

use Illuminate\Foundation\Http\FormRequest;

class ValidateCt extends FormRequest
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
            'name'=>['required','unique:brands,name'],
            'description' =>['required']
        ];
    }
    public function messages(){
        return[
            'name.required'=>'bạn phải nhập thông tin',
            'description.required'=>'bạn phải nhập thông tin',
            'name.unique'=>'tên đã tồn tại'
        ];
    }
}
