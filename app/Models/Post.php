<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $casts = [
        'body' => 'array'
    ];
    
    public function getTitleUpperCaseAttribute() {
        return strtoupper($this->title);
    }

    public function setTitleAttribute($value) {
        return $this->attributes['title'] = strtolower($value);
    }
    
    public function comments() {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function users() {
        return $this->belongsToMany(User::class, 'post-user', 'post_id', 'user-id');
    }
}
