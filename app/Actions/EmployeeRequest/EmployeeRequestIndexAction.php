<?php

namespace App\Actions\EmployeeRequest;

use App\Actions\Action;

class EmployeeRequestIndexAction extends Action
{

    protected $filter;

    public function __construct(array $filter)
    {
        $this->filter = $filter;
    }


    /**
     * @return mixed
     */
    public function __invoke()
    {
        $user = request()->user();
        return $user->requests()->when(isset($this->filter['status']) && !empty($this->filter['status']), function ($q) {
            $q->where('status', $this->filter['status']);
        })->orderBy('id', 'DESC')->get();
    }

}
