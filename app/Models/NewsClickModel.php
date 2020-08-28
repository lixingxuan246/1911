<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsClickModel extends Model
{
    public $table = 'news_click';
    public $primaryKey = 'id';
    public $timestamps=false;
}
