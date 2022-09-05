<?php

namespace App\Actions\Department;

use App\Actions\Action;
use App\Models\Department;

class DepartmentIndexAction extends Action
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function __invoke()
    {
        $departments = Department::with('sections')->get();
        return $departments->map(function ($val) {
            return [
                'id' => $val['id'],
                'name' => $val['name'],
                'sections' => $val['sections']->map(function ($value) {
                    return [
                        'id' => $value['id'],
                        'name' => $value['name'],
                    ];
                }),
            ];
        });
    }
}
