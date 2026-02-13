<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    // ✅ نخليها مفتوحة باش update يخدم
    protected $guarded = [];

    public function messages()
    {
        return $this->hasMany(\App\Models\Message::class);
    }
}
