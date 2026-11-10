<?php

namespace App\Http\Requests;

use App\Enums\EmployeeStatus;
use App\Enums\EmploymentType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = $this->route('company')->id;
        $employee = $this->route('employee');
        $hireDate = $this->input('hire_date', $employee->hire_date->toDateString());

        return [
            'department_id' => ['sometimes', 'nullable', 'integer', Rule::exists('departments', 'id')->where('company_id', $companyId)],
            'job_title_id' => ['sometimes', 'nullable', 'integrer', Rule::exists('job_titles', 'id')->where('company_id', $companyId)],
            'manager_id' => [
                'sometimes',
                'nullable',
                'integrer',
                Rule::exists('employees', 'id')->where('company_id', $companyId)->whereNull('deleted_at')->whereNot('id', $employee->id),
            ],
            'staff_number' => ['sometimes', 'required', 'string', 'max:20', Rule::unique('employees')->where('company_id', $companyId)->ignore($employee)],
            'first_name' => ['sometimes', 'required', 'string', 'max:100'],
            'last_name' => ['sometimes', 'required', 'string', 'max:100'],
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('employees')->where('company_id', $companyId)->ignore($employee)],
            'phone' => ['sometimes', 'nullable', 'string', 'max:20'],
            'national_id' => ['sometimes', 'required', 'digits_between:7,8'],
            'kra_pin' => ['sometimes', 'required', 'regex:/^A\d{9}[A-Z]$/', Rule::unique('employees')->where('company_id', $companyId)->ignore($employee)],
            'hire_date' => ['sometimes', 'required', 'date', 'before_or_equal:today'],
            'termination_date' => ['sometimes', 'nullable', 'date','after_or_equal:'.$hireDate],
            'employment_type' => ['sometimes', 'required', Rule::enum(EmploymentType::class)],
            'status' => ['sometimes', 'required', Rule::enum(EmployeeStatus::class)],
        ];
    }
}
