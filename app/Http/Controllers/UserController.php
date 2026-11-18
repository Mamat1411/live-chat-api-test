<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userService->getAllUsers();
        return ApiResponse::success(
            UserResource::collection($users),
            'Users retrieved successfully'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $validatedData = $request->validated();

        return DB::transaction(
            function () use ($validatedData) {
                $user = $this->userService->createNewUser($validatedData);
                return ApiResponse::success(
                    new UserResource($user),
                    'User created successfully',
                    201
                );
            }
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = new UserResource($this->userService->getUserById($id));

        return ApiResponse::success(
            $user,
            'User retrieved successfully'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $user = $this->userService->getUserById($id);

        return DB::transaction(
            function () use ($user, $validatedData) {
                $updatedUser = $this->userService->updateUser($user, $validatedData);
                return ApiResponse::success(
                    new UserResource($updatedUser),
                    'User updated successfully'
                );
            }
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = $this->userService->getUserById($id);

        return DB::transaction(
            function () use ($user) {
                $deletedUser = $this->userService->deleteUser($user);
                return ApiResponse::success(
                    new UserResource($deletedUser),
                    'User deleted successfully'
                );
            }
        );
    }
}
