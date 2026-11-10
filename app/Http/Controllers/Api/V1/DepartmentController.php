<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Company $company): AnonymousResourceCollection
    {
        $departments = $company->departments()->withCount('employees')->orderBy('name')->get();

        return DepartmentResource::collection($departments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request, Company $company): DepartmentResource
    {
        $department = $company->departments()->create($request->validated());

        return DepartmentResource::make($department);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company, Department $department): DepartmentResource
    {
        return DepartmentResource::make($department->loadCount('employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Company $company, Department $department): DepartmentResource
    {
        $department->update($request->validated());

        return DepartmentResource::make($department);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company, Department $department): Response
    {
        $department->delete();

        return response()->noContent();
    }
}
