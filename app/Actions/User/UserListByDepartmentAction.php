<?php

namespace App\Actions\User;

use App\Actions\Action;
use App\Models\Department;

class UserListByDepartmentAction extends Action
{

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function __invoke()
    {
        $dep = Department::where('id', $this->data['department_id'])->with('users')->first();
        return $dep->users;
    }

}
