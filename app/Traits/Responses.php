<?php

namespace App\Traits;

trait Responses
{

    /**
     * @param array $data
     * @param string $msg
     * @param int $httpStatus
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data = [], $msg = 'Success', $httpStatus = 200)
    {
        return response()->json([
            'status' => 1,
            'message' => $msg,
            'data' => $data,
        ], $httpStatus);
    }

    /**
     * @param $msg
     * @param array $data
     * @param int $httpStatus
     * @return \Illuminate\Http\JsonResponse
     */
    protected function paginatedResponse($data = [], $msg = 'Success', $httpStatus = 200)
    {
        $response['status'] = 1;
        $response['msg'] = $msg;
        $response['data'] = $data->items();
        $response['paginator']['currentPage'] = $data->currentPage();
        $response['paginator']['nextPage'] = $data->hasMorePages() ? $data->currentPage() + 1 : null;
        $response['paginator']['nextPageUrl'] = $data->nextPageUrl();
        $response['paginator']['previousPageUrl'] = $data->previousPageUrl();
        $response['paginator']['onFirstPage'] = $data->onFirstPage();
        $response['paginator']['perPage'] = $data->perPage();
        $response['paginator']['hasMorePages'] = $data->hasMorePages();
        return response()->json($response, $httpStatus);
    }

    /**
     * @param string $msg
     * @param int $httpStatus
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($msg = 'Error processing !', $httpStatus = 422)
    {
        return response()->json([
            'status' => 0,
            'message' => $msg,
        ], $httpStatus);
    }
}
