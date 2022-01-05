<?php

namespace App\Models;

use App\Abstracts\ListingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    public function board(){
        return $this->belongsTo(Board::class);
    }

    public function cards(){
        return $this->hasMany(Card::class);
    }

    public static function storeListings($boardId){
        Listing::insert([
            ['type' => ListingType::TODO, 'board_id' => $boardId],
            ['type' => ListingType::DOING, 'board_id' => $boardId],
            ['type' => ListingType::DONE, 'board_id' => $boardId],
        ]);
    }
}
