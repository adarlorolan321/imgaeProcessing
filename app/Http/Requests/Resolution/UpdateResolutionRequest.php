<?php

namespace App\Http\Requests\Resolution;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResolutionRequest extends FormRequest
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
            "title" => ["required"],"resolution_no"=> ["required"], "date" => ["required"],"term" => ["required"],"description" => ["nullable"],"photo" => ["nullable"],
        ];
    }
}
