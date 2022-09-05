<?php

namespace App\Http\Controllers\Api;

use App\Actions\Department\DepartmentIndexAction;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class DepartmentsController extends Controller
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $response = App::call(new DepartmentIndexAction);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->successResponse($response);
    }
}
