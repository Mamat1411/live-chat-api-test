<?php

namespace App\Services;

use App\Models\Room;
use Illuminate\Pagination\LengthAwarePaginator;

class RoomService
{
    public function __construct(
        private Room $room
    ) {
    }

    public function getAllRooms(): LengthAwarePaginator
    {
        return $this->room->with('creator:id,name')->latest('last_message_at')->paginate(10, ["id", "name", "created_by", "last_message_at", "created_at", "updated_at"]);
    }

    public function getRoomById(string $id): Room {
        return $this->room->with('creator:id,name')->findOrFail($id);
    }

    public function getRoomByUserId(string $id): LengthAwarePaginator {
        return $this->room->with('creator:id,name')->where('created_by', $id)->latest('last_message_at')->paginate(10, ["id", "name", "created_by", "last_message_at", "created_at", "updated_at"]);
    }

    public function insertNewRoom(array $data): Room
    {
        return $this->room->create($data);
    }

    public function updateRoom(Room $room, array $data): Room
    {
        $room->update($data);
        return $room;
    }

    public function deleteRoom(Room $room): Room
    {
        $room->delete();
        return $room;
    }
}
