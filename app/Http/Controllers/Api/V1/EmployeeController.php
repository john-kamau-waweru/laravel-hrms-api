<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\EmployeeStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    private const RELATIONS = ['department', 'jobTitle', 'manager'];
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Company $company): AnonymousResourceCollection
    {
        $filters = $request->validate([
            'status' => ['sometimes', Rule::enum(EmployeeStatus::class)],
            'department_id' => ['sometimes', 'integer'],
            'job_title_id' => ['sometimes', 'integer'],
            'search' => ['sometimes', 'string', 'max:100'],
            'per_page' => ['sometimes', 'integer', 'between:1,100'],
        ]);

        $employees = $company->employees()
            ->with(self::RELATIONS)
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['department_id'] ?? null, fn ($query, $id) => $query->where('department_id', $id))
            ->when($filters['job_title_id'] ?? null, fn ($query, $id) => $query->where('job_title_id', $id))
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', "{$search}%")
                        ->orWhere('last_name', 'like', "{$search}%")
                        ->orWhere('staff_number', 'like', "{$search}%");
                });
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate($filters['per_page'] ?? 25)
            ->withQueryString();

        return EmployeeResource::collection($employees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request, Company $company): EmployeeResource
    {
        $employee = $company->employees()->create($request->validated());

        return EmployeeResource::make($employee->load(self::RELATIONS));
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company, Employee $employee): EmployeeResource
    {
        return EmployeeResource::make($employee->load(self::RELATIONS));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Company $company, Employee $employee): EmployeeResource
    {
        $employee->update($request->validated());

        return EmployeeResource::make($employee->load(self::RELATIONS));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company, Employee $employee): Response
    {
        $employee->delete();

        return response()->noContent();
    }
}
