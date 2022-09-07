<?php

namespace App\Http\Controllers\Api;

use App\Actions\User\UserListByDepartmentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserListByDepartmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class UsersController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listByDepartment(UserListByDepartmentRequest $request)
    {
        try {
            $response = App::call(new UserListByDepartmentAction($request->only(['department_id'])));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->successResponse($response);

    }
}
