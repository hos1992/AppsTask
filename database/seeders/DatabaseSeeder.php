<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Department;
use App\Models\User;
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
        $hrDepartmentManager = User::firstOrCreate(['email' => 'hr@hr.com'], [
            'name' => 'Hr Manager',
            'email' => 'hr@hr.com',
            'password' => bcrypt('123456'),
        ]);
        $hrDepartmentManager->departments()->sync([
            ['department_id' => $hr->id, 'is_manager' => true]
        ]);

        $salesDepartmentManager = User::firstOrCreate(['email' => 'sales@sales.com'], [
            'name' => 'Sales Manager',
            'email' => 'sales@sales.com',
            'password' => bcrypt('123456'),
        ]);
        $salesDepartmentManager->departments()->sync([
            ['department_id' => $sales->id, 'is_manager' => true]
        ]);

        $devDepartmentManager = User::firstOrCreate(['email' => 'dev@dev.com'], [
            'name' => 'Dev Manager',
            'email' => 'dev@dev.com',
            'password' => bcrypt('123456'),
        ]);
        $devDepartmentManager->departments()->sync([
            ['department_id' => $development->id, 'is_manager' => true]
        ]);


    }
}
