<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'listing_id',
        'index'
    ];

    public function listing(){
        return $this->belongsTo(Listing::class);
    }
}
