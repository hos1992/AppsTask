<?php

namespace App\Actions\EmployeeRequest;

use App\Actions\Action;
use App\Models\Department;
use App\Notifications\NewEmployeeRequestNotification;
use Illuminate\Support\Facades\Notification;

class EmployeeRequestStoreAction extends Action
{

    protected $data;
    protected $usersToNotify;

    public function __construct(array $data)
    {
        $this->data = $data;
    }


    public function __invoke()
    {
        $user = request()->user();

        $create['description'] = $this->data['description'];

        if (is_null($user->section_id)) {
            if ($user->department->name == 'hr') {
                $create['status'] = 2;
            } else {
                $create['status'] = 1;
                $this->usersToNotify[] = Department::where('name', 'hr')->first()->manager;
            }
        } else {
            if ($user->department->name == 'hr') {
                $create['status'] = 1;
            }
            $this->usersToNotify[] = $user->department->manager;
        }

        $request = $user->requests()->create($create);

        // send notification here
        if (count($this->usersToNotify)) {
            Notification::send($this->usersToNotify, new NewEmployeeRequestNotification($request));
        }

        return $request;
    }

}
