<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $companies = Company::query()->withCount('employees')->orderBy('name')->paginate(25);

        return CompanyResource::collection($companies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        $company = Company::create($request->validated());
        
        return CompanyResource::make($company);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company): CompanyResource
    {
        return CompanyResource::make($company->loadCount('employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company): CompanyResource
    {
        $company->update($request->validated());

        return CompanyResource::make($company);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company): Response
    {
        $company->delete();

        return response()->noContent();
    }
}
