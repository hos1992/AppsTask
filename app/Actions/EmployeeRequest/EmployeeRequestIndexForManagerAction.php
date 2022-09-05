<?php

namespace App\Actions\EmployeeRequest;

use App\Actions\Action;
use App\Models\EmployeeRequest;
use Illuminate\Validation\ValidationException;

class EmployeeRequestIndexForManagerAction extends Action
{

    protected $filter;

    public function __construct(array $filter)
    {
        $this->filter = $filter;
    }
    
    /**
     * @return mixed
     */
    public function __invoke()
    {
        $user = request()->user();

        if (!is_null($user->section_id)) {
            throw ValidationException::withMessages([
                'message' => ['You don\'t have access to this action'],
            ]);
        }
        return EmployeeRequest::when($user->department->name != 'hr', function ($q) use ($user) {
            $q->whereHas('user', function ($q) use ($user) {
                $q->where('department_id', $user->department_id);
            })->where([
                ['status', '=', 0]
            ]);
        })->when($user->department->name == 'hr', function ($q) use ($user) {
            $q->where([
                ['status', '=', 1]
            ]);
        })->orderBy('id', 'DESC')->get();

    }

}
