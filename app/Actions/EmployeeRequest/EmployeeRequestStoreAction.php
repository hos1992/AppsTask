<?php

namespace App\Actions\EmployeeRequest;

use App\Actions\Action;
use App\Models\Department;
use App\Models\User;
use App\Notifications\NewEmployeeRequestNotification;
use Illuminate\Support\Facades\Auth;
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

        $user = User::where('id', Auth::id())->with('departments.manager', 'sections')->first();
        $isManagerInAnyDepartment = false;
        $isHrManager = false;
        $isInHrDepartment = false;
        $hrManager = Department::where('name', 'hr')->with('manager')->first();

        foreach ($user->departments as $dep) {
            if ($dep->pivot->is_manager) {
                $isManagerInAnyDepartment = true;
            }
            if ($dep->name == 'hr' && $dep->pivot->is_manager) {
                $isHrManager = true;
            }
            if ($dep->name == 'hr') {
                $isInHrDepartment = true;
            }
        }

        if ($isManagerInAnyDepartment) {
            $create['status'] = 1;
            if ($hrManager->manager->user) {
                $this->usersToNotify[] = $hrManager->manager->user;
            }
        }

        if ($isManagerInAnyDepartment && $isHrManager) {
            $create['status'] = 2;
        }

        if (!$isManagerInAnyDepartment && !$isHrManager) {
            if ($isInHrDepartment) {
                if ($hrManager->manager->user) {
                    $this->usersToNotify[] = $hrManager->manager->user;
                }
            } else {
                foreach ($user->departments as $dep) {
                    if ($dep->manager) {
                        $this->usersToNotify[] = $dep->manager->user;
                    }
                }
            }
        }

        $create['description'] = $this->data['description'];
        $request = $user->requests()->create($create);

        // send notification here
        if (count($this->usersToNotify)) {
            Notification::send($this->usersToNotify, new NewEmployeeRequestNotification($request));
        }

        return $request;
    }

}
