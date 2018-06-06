<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public function users()
    {
        return $this->belongsToMany('App\User', 'item_user', 'item_id', 'user_id');
    }
}
