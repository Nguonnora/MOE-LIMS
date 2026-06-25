<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Permission helpers
    protected function canViewWorkOrders()
    {
        return in_array(auth()->user()->role, ['admin', 'technician', 'approver', 'receptionist', 'viewer']);
    }

    protected function canCreateWorkOrder()
    {
        return in_array(auth()->user()->role, ['admin', 'receptionist']);
    }

    protected function canEditWorkOrder()
    {
        return in_array(auth()->user()->role, ['admin', 'receptionist']);
    }

    protected function canDeleteWorkOrder()
    {
        return auth()->user()->role == 'admin';
    }

    protected function canViewTestResults()
    {
        return in_array(auth()->user()->role, ['admin', 'technician', 'approver', 'viewer']);
    }

    protected function canEnterResults()
    {
        return in_array(auth()->user()->role, ['admin', 'technician']);
    }

    protected function canApproveResults()
    {
        return in_array(auth()->user()->role, ['admin', 'approver']);
    }

    protected function canViewUsers()
    {
        return auth()->user()->role == 'admin';
    }

    protected function canManageTestParameters()
    {
        return auth()->user()->role == 'admin';
    }

    // Generic check and abort
    protected function checkPermission($method, $message = 'Unauthorized')
    {
        if (!method_exists($this, $method) || !$this->$method()) {
            abort(403, $message);
        }
    }
}