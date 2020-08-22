<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsUserModel extends Model
{
    public $table = 'news_user';
    public $primaryKey = 'user_id';
    public $timestamps=false;
}
