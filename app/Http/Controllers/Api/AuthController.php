<?php

namespace App\Http\Controllers\Api;

use App\Actions\Auth\UserLoginAction;
use App\Actions\Auth\UserRegisterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserLoginRequest;
use App\Http\Requests\Api\UserRegisterRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AuthController extends Controller
{

    /**'
     * @param UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserLoginRequest $request)
    {
        try {
            $response = App::call(new UserLoginAction($request->only(['email', 'password'])));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->successResponse(['token' => $response]);
    }


    /**
     * @param UserRegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRegisterRequest $request)
    {
        try {
            $response = App::call(new UserRegisterAction($request->only([
                'name', 'email', 'password', 'department_id', 'section_id',
            ])));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->successResponse($response);
    }
}
