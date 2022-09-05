<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Department;
use App\Models\Section;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // create the departments
        $hr = Department::firstOrCreate(['name' => 'hr']);
        $sales = Department::firstOrCreate(['name' => 'sales']);
        $development = Department::firstOrCreate(['name' => 'development']);

        // create the section for each department
        $hrTeamSection = $hr->sections()->firstOrCreate(['name' => 'hr team']);
        $inDoorSalesSection = $sales->sections()->firstOrCreate(['name' => 'in door']);
        $outDoorSalesSection = $sales->sections()->firstOrCreate(['name' => 'out door']);
        $frontEndDevSection = $development->sections()->firstOrCreate(['name' => 'front end team']);
        $backEndDevSection = $development->sections()->firstOrCreate(['name' => 'back end team']);

        // create the users
        $hrDepartmentManager = $hr->users()->firstOrCreate(['email' => 'hr@hr.com'], [
            'name' => 'Hr Manager',
            'email' => 'hr@hr.com',
            'password' => bcrypt('123456'),
        ]);
        $hrDepartmentSectionEmployee = $hr->users()->firstOrCreate(['email' => 'hrEmployee@hr.com'], [
            'name' => 'Hr Employee',
            'email' => 'hrEmployee@hr.com',
            'password' => bcrypt('123456'),
            'section_id' => $hrTeamSection->id
        ]);
        $salesDepartmentManager = $sales->users()->firstOrCreate(['email' => 'sales@sales.com'], [
            'name' => 'Sales Manager',
            'email' => 'sales@sales.com',
            'password' => bcrypt('123456'),
        ]);
        $salesDepartmentSectionIndoorEmployee = $sales->users()->firstOrCreate(['email' => 'inDoorEmployee@sales.com'], [
            'name' => 'In Door Employee',
            'email' => 'inDoorEmployee@hr.com',
            'password' => bcrypt('123456'),
            'section_id' => $inDoorSalesSection->id
        ]);
        $salesDepartmentSectionOutdoorEmployee = $sales->users()->firstOrCreate(['email' => 'outDoorEmployee@sales.com'], [
            'name' => 'Out Door Employee',
            'email' => 'outDoorEmployee@hr.com',
            'password' => bcrypt('123456'),
            'section_id' => $outDoorSalesSection->id
        ]);
        $devDepartmentManager = $development->users()->firstOrCreate(['email' => 'dev@dev.com'], [
            'name' => 'Dev Manager',
            'email' => 'dev@dev.com',
            'password' => bcrypt('123456'),
        ]);
        $devDepartmentSectionFrontEmployee = $development->users()->firstOrCreate(['email' => 'frontEmployee@dev.com'], [
            'name' => 'Front End Employee',
            'email' => 'frontEmployee@dev.com',
            'password' => bcrypt('123456'),
            'section_id' => $frontEndDevSection->id
        ]);
        $devDepartmentSectionBackEmployee = $development->users()->firstOrCreate(['email' => 'backEmployee@dev.com'], [
            'name' => 'Back End Employee',
            'email' => 'backEmployee@dev.com',
            'password' => bcrypt('123456'),
            'section_id' => $backEndDevSection->id
        ]);
    }
}
