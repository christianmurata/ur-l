<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class Url extends Model
{
    use HasFactory;

    public function get($key) 
    {
        return Redis::get($key);
    }

    public function set($url) 
    {
        return Redis::set($key, $url);
    }

    public function shortUrl($url)
    {
        return substr(md5(time(). $url), 0, 5);
    }

}
