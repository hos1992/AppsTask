<?php

namespace App\Actions\Auth;

use App\Actions\Action;
use App\Models\Department;
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
        $department = Department::find($this->data['department_id']);
        if (!isset($this->data['section_id']) || empty($this->data['section_id'])) {
            $checkForManager = User::where([
                ['department_id', '=', $department->id],
                ['section_id', '=', null]
            ])->first();
            if ($checkForManager) {
                throw ValidationException::withMessages([
                    'section_id' => ['The section id is required because the department already has a manager'],
                ]);
            }
        } else {
            if (!$department->sections()->where('id', $this->data['section_id'])->first()) {
                throw ValidationException::withMessages([
                    'section_id' => ['Wrong section id'],
                ]);
            }
        }



        $this->data['password'] = bcrypt($this->data['password']);
        $user =  $department->users()->create($this->data);
        $user['token'] = 'Bearer ' . $user->createToken($user->name)->plainTextToken;
        return $user;
    }
}
