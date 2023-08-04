<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_participants', 'message_id', 'user_id');
    }
    public function room(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ChatRoom::class);
    }
    public function markAsSeen(): void
    {
        $this->seen_status = true;
        $this->save();
    }
}
