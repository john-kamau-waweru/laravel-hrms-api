<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobTitleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $company = $this->route('company');
        $jobTitle = $this->route('job_title');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('job_titles', 'name')->where('company_id', $company->id)->ignore($jobTitle)],
        ];
    }
}
