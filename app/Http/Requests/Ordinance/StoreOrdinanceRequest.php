<?php

namespace App\Http\Requests\Ordinance;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrdinanceRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            "status" => ["required"],"title" => ["required"],"description" => ["nullable"],"date" => ["required"],"term" => ["required"],"ordinance_no" => ["required"],"photo" => ["nullable"],
        ];
    }
}
