<?php

namespace App\Actions\Auth;

use App\Actions\Action;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserLoginAction extends Action
{

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }


    /**
     * Authenticate the user
     *
     * @return String
     */
    public function __invoke()
    {
        $user = User::where('email', $this->data['email'])->first();

        if (!$user || !Hash::check($this->data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return 'Bearer ' . $user->createToken($user->name)->plainTextToken;
    }
}
