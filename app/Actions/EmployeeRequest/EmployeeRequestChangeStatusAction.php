<?php

namespace App\Actions\EmployeeRequest;

use App\Actions\Action;
use App\Models\Department;
use App\Models\EmployeeRequest;
use App\Notifications\EmployeeRequestStatusChangedNotification;
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
        $user = request()->user();
        $currentDepartment = $user->department;
        $req = EmployeeRequest::where('id', $this->data['request_id'])->with('user')->first();

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

        if (!is_null($user->section_id)) {
            throw ValidationException::withMessages([
                'message' => ['You don\'t have access to this action'],
            ]);
        }

        if ($currentDepartment->name != 'hr' && $currentDepartment->id != $req->user->department_id) {
            throw ValidationException::withMessages([
                'message' => ['You don\'t have access to this action'],
            ]);
        }

        if ($req->status == 0 && $user->department_id != $req->user->department_id) {
            throw ValidationException::withMessages([
                'message' => ['You don\'t have access to this action'],
            ]);
        }

        if ($currentDepartment->name != 'hr' && $this->data['status'] == 2) {
            throw ValidationException::withMessages([
                'message' => ['You don\'t have the rights to send this status'],
            ]);
        }

        /**
         * Update the status
         */
        $req->status = $this->data['status'];
        $req->save();


        if ($this->data['status'] == 1) {
            $this->usersToNotify[] = $req->user;
            $this->usersToNotify[] = Department::where('name', 'hr')->first()->manager;
        }
        if ($this->data['status'] == 2 || $this->data['status'] == 3) {
            $this->usersToNotify[] = $req->user;
        }

        Notification::send($this->usersToNotify, new EmployeeRequestStatusChangedNotification($req));

        return $req;

    }

}
