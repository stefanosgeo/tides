<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function clips()
    {
        return $this->hasMany(Clip::class);
    }

    public function path()
    {
        return "/series/{$this->slug}";
    }
}
