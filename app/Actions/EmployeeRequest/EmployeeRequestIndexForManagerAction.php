<?php

namespace App\Actions\EmployeeRequest;

use App\Actions\Action;
use App\Models\EmployeeRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

        $user = User::where('id', Auth::id())->with('departmentsIManage.users.requestsForManager.user', 'sections')->first();
        $isHrManager = false;

        if (!count($user->departmentsIManage)) {
            throw ValidationException::withMessages([
                'error' => ['You are not a manager in any department !'],
            ]);
        }


        $requests = [];
        foreach ($user->departmentsIManage as $dep) {
            if ($dep->name == 'hr') {
                $isHrManager = true;
            }

            foreach ($dep->users as $employee) {
                foreach ($employee->requestsForManager as $req) {
                    $requests[] = $req;
                }
            }
        }

        if ($isHrManager) {
            return EmployeeRequest::where('status', 1)->with('user')->orderBy('id', 'DESC')->get();
        }

        return array_values(collect($requests)->sortByDesc('id')->toArray());
    }

}
