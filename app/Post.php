<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    protected $fillable = [
        'category_id',
        'photo_id',
        'title',
        'body'
    ];

    //Setting up the relationship between the Post and the User (One-To-One Relationship)
    public function user() {
        return $this->belongsTo('App\User');
    }

    //Setting up the relationship between the Post and the Photo (One-To-One Relationship)
    public function photo() {
        return $this->belongsTo('App\Photo');
    }

    //Setting up the relationship between the Post and the Category (One-To-One Relationship)
    public function category() {
        return $this->belongsTo('App\Category');
    }
}
