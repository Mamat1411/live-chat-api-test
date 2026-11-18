<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    /** @use HasFactory<\Database\Factories\RoomFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [
        "id",
        "created_at",
        "updated_at",
    ];

    /**
     * Get the user that owns the Room
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, "created_by", "id")->withTrashed();
    }

    public function members() : BelongsToMany {
        return $this->belongsToMany(User::class, "room_user")
            ->withPivot("last_read_at")
            ->withTimestamps();
    }

    public function hasMembers(string $userId) : bool {
        return $this->members()->whereKey($userId)->exists();
    }
}
