<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
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
        try {
            return response()->json([
                "status" => 200,
                "message" => "Users retrieved successfully",
                "data" => UserResource::collection($users)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => 500,
                "message" => "Failed to retrieve users"
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $validatedData = $request->validated();

        DB::beginTransaction();
        try {
            $user = $this->userService->createNewUser($validatedData);
            DB::commit();
            return response()->json([
                "status" => 201,
                "message" => "User created successfully",
                "data" => new UserResource($user)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "status" => 500,
                "message" => "Failed to create user"
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = new UserResource($this->userService->getUserById($id));

        try {
            return response()->json([
                "status" => 200,
                "message" => "User retrieved successfully",
                "data" => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => 500,
                "message" => "Failed to retrieve user"
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $user = $this->userService->getUserById($id);

        DB::beginTransaction();
        try {
            $updatedUser = $this->userService->updateUser($user, $validatedData);
            DB::commit();
            return response()->json([
                "status" => 200,
                "message" => "User updated successfully",
                "data" => new UserResource($updatedUser)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "status" => 500,
                "message" => "Failed to update user"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = $this->userService->getUserById($id);

        DB::beginTransaction();
        try {
            $deletedUser = $this->userService->deleteUser($user);
            DB::commit();
            return response()->json([
                "status" => 200,
                "message" => "User deleted successfully",
                "data" => new UserResource($deletedUser)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "status" => 500,
                "message" => "Failed to delete user"
            ]);
        }
    }
}
