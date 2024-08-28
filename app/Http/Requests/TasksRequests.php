<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TasksRequests extends FormRequest
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
            'title' => 'required',
            'description' => 'required',
            'start_at' => 'required',
            'expire_at' => 'required',
            'users' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'عنوان را وارد کنید',
            'description.required' => 'توضیحات را وارد کنید',
            'start_at.required' => 'تاریخ شروع را وارد کنید',
            'expire_at.required' => 'تاریخ پایان را وارد کنید',
            'users.required' => 'کارمندان مورد نظر را انتخاب کنید',
        ];
    }
}
