<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'user_id'];

    public function setTitleAttribute($value){
        $this->attributes['title'] = ucfirst($value);
    }

    public function listings(){
        return $this->hasMany(Listing::class);
    }
}
