<?php

namespace App\Http\Requests;

use App\Enums\EmployeeStatus;
use App\Enums\EmploymentType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = $this->route('company')->id;

        return [
            'department_id' => ['nullable', 'integer', Rule::exists('departments', 'id')->where('company_id', $companyId)],
            'job_title_id' => ['nullable', 'integer', Rule::exists('job_titles', 'id')->where('company_id', $companyId)],
            'manager_id' => [
                'nullable',
                'integer',
                Rule::exists('employees', 'id')->where('company_id', $companyId)->whereNull(('deleted_at')),
            ],
            'staff_number' => ['required', 'string', 'max:20', Rule::unique('employees')->where('company_id', $companyId)],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('employees')->where('company_id', $companyId)],
            'phone' => ['nullable', 'string', 'max:20'],
            'national_id' => ['required', 'string', 'max:20'],
            'kra_pin' => ['required', 'regex:/^A\d{9}[A-Z]$/', Rule::unique('employees')->where('company_id', $companyId)],
            'hire_date' => ['required', 'date', 'before_or_equal:today'],
            'employment_type' => ['required', Rule::enum(EmploymentType::class)],
            'status' => ['sometimes', Rule::enum(EmployeeStatus::class)],
        ];
    }
}
