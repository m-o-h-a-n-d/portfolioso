<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UserRequest;
use App\Http\Resources\User\UserResource;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function index()
    {
        $user = $this->userService->getUserAuth();

        if (! $user) {
            return apiResponce(401, 'Unauthorize');
        }

        return apiResponce(200, 'Success', new UserResource($user));
    }

    public function getUserData()
    {
        $user = $this->userService->getUserData();

        if (! $user) {
            return apiResponce(404, 'Not Found');
        }

        return apiResponce(200, 'Success', new UserResource($user));
    }

    public function update(UserRequest $request)
    {
        $data = $request->validated();

        $user = $this->userService->update($data);

        if (! $user) {
            return apiResponce(400, 'Bad Request');
        }

        return apiResponce(200, 'Success', new UserResource($user));
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $data = $request->validated();

        if (! $this->userService->updatePassword($data)) {
            return apiResponce(400, 'Bad Request');
        }

        return apiResponce(200, 'Password updated successfully');
    }
}
