<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTokenModel extends Model
{
    public $table = 'news_user_token';
    public $primaryKey = 'id';
    public $timestamps=false;
}
