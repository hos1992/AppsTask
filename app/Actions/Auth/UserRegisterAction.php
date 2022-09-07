<?php

namespace App\Actions\Auth;

use App\Actions\Action;
use App\Models\Department;
use App\Models\DepartmentUser;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserRegisterAction extends Action
{

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Register new user
     *
     * @return void
     */
    public function __invoke()
    {

        $userDepartments = [];
        $userSections = [];

        $departments = $this->data['departments'];
        foreach ($departments as $department) {
            $dep = Department::find($department['id']);
            if (isset($department['section_id']) && !empty($department['section_id'])) {
                $section = $dep->sections()->where('id', $department['section_id'])->first();
                if (!$section) {
                    throw ValidationException::withMessages([
                        'error' => ['The section id ( ' . $department['section_id'] . ' ) provided for the wrong department'],
                    ]);
                }

                $userSections[] = [
                    'section_id' => $section->id,
                ];

            } else {
                // this user wanted to be a manger
                $checkForManager = DepartmentUser::where([
                    ['department_id', '=', $dep->id],
                    ['is_manager', '=', true],
                ])->first();

                if ($checkForManager) {
                    throw ValidationException::withMessages([
                        'error' => ['The department id ( ' . $dep->id . ' )  already has a manager'],
                    ]);
                }
            }

            $userDepartments[] = [
                'department_id' => $dep->id,
                'is_manager' => empty($department['section_id']) ? true : false,
            ];

        }


        unset($this->data['departments']);
        $this->data['password'] = bcrypt($this->data['password']);
        $user = User::create($this->data);
        $user['token'] = 'Bearer ' . $user->createToken($user->name)->plainTextToken;

        if ($user) {
            $user->departments()->sync($userDepartments);
            $user->sections()->sync($userSections);
        }

        return $user;
    }
}
