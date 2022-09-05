<?php

namespace App\Http\Controllers\Api;

use App\Actions\EmployeeRequest\EmployeeRequestChangeStatusAction;
use App\Actions\EmployeeRequest\EmployeeRequestIndexAction;
use App\Actions\EmployeeRequest\EmployeeRequestIndexForManagerAction;
use App\Actions\EmployeeRequest\EmployeeRequestStoreAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EmployeeRequestChangeStatusRequest;
use App\Http\Requests\Api\EmployeeRequestStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;


class EmployeeRequestsController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $response = App::call(new EmployeeRequestIndexAction($request->only(['status'])));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
        return $this->successResponse($response);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listRequestsForManager(Request $request)
    {
        try {
            $response = App::call(new EmployeeRequestIndexForManagerAction($request->only(['status'])));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
        return $this->successResponse($response);
    }

    /**
     * @param EmployeeRequestStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EmployeeRequestStoreRequest $request)
    {
        try {
            $response = App::call(new EmployeeRequestStoreAction($request->only(['description'])));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
        return $this->successResponse($response);
    }

    /**
     * @param EmployeeRequestChangeStatusRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeRequestStatus(EmployeeRequestChangeStatusRequest $request)
    {
        try {
            $response = App::call(new EmployeeRequestChangeStatusAction($request->only(['request_id', 'status'])));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
        return $this->successResponse($response);
    }
}
