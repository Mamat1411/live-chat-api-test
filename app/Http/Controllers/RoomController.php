<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomRequest;
use App\Http\Resources\RoomResource;
use App\Http\Responses\ApiResponse;
use App\Models\Room;
use App\Services\RoomService;
use Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function __construct(
        private RoomService $roomService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize("viewAny", Room::class);
        $rooms = $this->roomService->getAllRooms();
        return ApiResponse::paginated(
            RoomResource::collection($rooms),
            'Rooms retrieved successfully'
        );
    }

    public function getRoomByUserId(): JsonResponse {
        $rooms = $this->roomService->getRoomByUserId(Auth::user()->id);

        return ApiResponse::paginated(
            RoomResource::collection($rooms),
            'Rooms retrieved successfully'
        );
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(RoomRequest $request)
    {
        $validatedData = $request->validated();
        $createdBy = auth()->user()->id;
        $validatedData["created_by"] = $createdBy;

        return DB::transaction(
            function () use ($validatedData) {
                $room = $this->roomService->insertNewRoom($validatedData);
                return ApiResponse::success(
                    new RoomResource($room),
                    'Room created successfully',
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
        $room = $this->roomService->getRoomById($id);
        Gate::authorize("view", $room);

        return ApiResponse::success(
            new RoomResource($room),
            'Room retrieved successfully'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoomRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $room = $this->roomService->getRoomById($id);
        Gate::authorize('update', $room);

        return DB::transaction(
            function () use ($room, $validatedData) {
                $updatedRoom = $this->roomService->updateRoom($room, $validatedData);
                return ApiResponse::success(
                    new RoomResource($updatedRoom),
                    'Room updated successfully'
                );
            }
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = $this->roomService->getRoomById($id);
        Gate::authorize('delete', $room);

        return DB::transaction(
            function () use ($room) {
                $deletedRoom = $this->roomService->deleteRoom($room);
                return ApiResponse::success(
                    new RoomResource($deletedRoom),
                    'Room deleted successfully'
                );
            }
        );
    }
}
