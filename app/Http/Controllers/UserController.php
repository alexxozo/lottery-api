<?php

namespace App\Http\Controllers;

use App\Services\UserService;

class UserController extends BaseAbstractController
{
    public function __construct(UserService $service)
    {
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'string',
            'is_admin' => 'numeric',
        ];
        parent::__construct($service, 'App\Models\User', $rules);
    }
}
