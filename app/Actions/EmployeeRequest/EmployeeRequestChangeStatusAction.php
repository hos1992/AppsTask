<?php

namespace App\Actions\EmployeeRequest;

use App\Actions\Action;
use App\Models\Department;
use App\Models\EmployeeRequest;
use App\Models\User;
use App\Notifications\EmployeeRequestStatusChangedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class EmployeeRequestChangeStatusAction extends Action
{

    protected $data;
    protected $usersToNotify;

    public function __construct(array $data)
    {
        $this->data = $data;
    }


    /**
     * @return mixed
     * @throws ValidationException
     */
    public function __invoke()
    {

        $user = User::where('id', Auth::id())->with('departmentsIManage')->first();
        $req = EmployeeRequest::where('id', $this->data['request_id'])->with('user.departments')->first();
        $isHrManager = false;

        if (!count($user->departmentsIManage)) {
            throw ValidationException::withMessages([
                'error' => ['You are not a manager in any department !'],
            ]);
        }

        foreach ($user->departmentsIManage as $dep) {
            if ($dep->name == 'hr') {
                $isHrManager = true;
            }
        }

        if ($req->status == $this->data['status']) {
            throw ValidationException::withMessages([
                'message' => ['The request already on this status'],
            ]);
        }

        if ($req->status > $this->data['status']) {
            throw ValidationException::withMessages([
                'message' => ['You can not downgrade to previous status PLZ create new request !'],
            ]);
        }

        if ($req->status == 2 && $this->data['status'] == 3) {
            throw ValidationException::withMessages([
                'message' => ['You can not reject a request after approval'],
            ]);
        }

        $myDepartmentsIds = $user->departmentsIManage->pluck('id')->toArray();
        $employeeDepartmentsIds = $req->user->departments->pluck('id')->toArray();

        if (!$isHrManager) {
            $checkDepartment = array_intersect($myDepartmentsIds, $employeeDepartmentsIds);
            if (!count($checkDepartment)) {
                throw ValidationException::withMessages([
                    'error' => ['You don\'t have permission to change this request status because it\'s not in a department you manage'],
                ]);
            }
        } else {
            if ($this->data['status'] < 1) {
                throw ValidationException::withMessages([
                    'error' => ['the manager must approve the request before the hr !'],
                ]);
            }
        }

        /**
         * Update the status
         */
        $req->status = $this->data['status'];
        $req->save();


        if ($this->data['status'] == 1) {
            $hrManager = Department::where('name', 'hr')->with('manager')->first();
            $this->usersToNotify[] = $req->user;
            $this->usersToNotify[] = $hrManager->manager->user;
        }
        if ($this->data['status'] == 2 || $this->data['status'] == 3) {
            $this->usersToNotify[] = $req->user;
        }

        Notification::send($this->usersToNotify, new EmployeeRequestStatusChangedNotification($req));

        return $req;

    }

}
