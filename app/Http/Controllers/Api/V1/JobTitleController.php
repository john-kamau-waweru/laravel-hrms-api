<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobTitleRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\JobTitleResource;
use App\Models\Company;
use App\Models\JobTitle;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class JobTitleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Company $company): AnonymousResourceCollection
    {
        $jobTitles = $company->jobTitles()->withCount('employees')->orderBy('name')->get();

        return EmployeeResource::collection($jobTitles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobTitleRequest $request, Company $company): JobTitleResource
    {
        $jobTitle = $company->jobTitles()->create($request->validated());

        return JobTitleResource::make($jobTitle);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company, JobTitle $jobTitle): JobTitleResource
    {
        return JobTitleResource::make($jobTitle->loadCount('employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company, JobTitle $jobTitle): JobTitleResource
    {
        $jobTitle->update($request->validated());

        return JobTitleResource::make($jobTitle);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company, JobTitle $jobTitle): Response
    {
        $jobTitle->delete();

        return response()->noContent();
    }
}
