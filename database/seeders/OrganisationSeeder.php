<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganisationSeeder extends Seeder
{
    private const EMPLOYEES_PER_COMPANY = 2000;
    private const DEPARTMENTS = [
        'FIN' => 'Finance',
        'HR' => 'Human Resources',
        'ENG' => 'Engineering',
        'OPS' => 'Operations',
        'SAL' => 'Sales',
    ];

    private const JOB_TITLES = [
        'Head of Department',
        'Senior Officer',
        'Officer',
        'Associate',
        'Intern'
    ];

    public function run(): void
    {
        Company::factory()->count(3)->create()->each(fn (Company $company) => $this->seedCompany($company));
    }

    private function seedCompany(Company $company): void
    {
        $titles = collect(self::JOB_TITLES)->map(fn (string $name) => $company->jobTitles()->create(['name' => $name]))->keyBy('name');

        $staffTitles = $titles->except('Head of Department');

        $departments = collect(self::DEPARTMENTS)->map(fn (string $name, string $code) => $company->departments()->create([
            'name' => $name,
            'code' => $code,
        ]));

        $perDepartment = intdiv(self::EMPLOYEES_PER_COMPANY, $departments->count());

        foreach ($departments as $department) {
            $head = Employee::factory()->create([
                'company_id' => $company->id,
                'department_id' => $department->id,
                'job_title_id' => $titles['Head of Department']->id,
            ]);

            Employee::factory()->count($perDepartment - 1)->create([
                'company_id' => $company->id,
                'department_id' => $department->id,
                'job_title_id' => fn () => $staffTitles->random()->id,
                'manager_id' => $head->id,
            ]);
        }
    }
}
