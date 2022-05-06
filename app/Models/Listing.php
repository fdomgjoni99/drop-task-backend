<?php

namespace App\Models;

use App\Abstracts\ListingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'index', 'board_id'];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public static function storeListings($boardId)
    {
        Listing::insert([
            ['type' => 'Todo', 'board_id' => $boardId, 'index' => 0],
            ['type' => 'Doing', 'board_id' => $boardId, 'index' => 1],
            ['type' => 'Done', 'board_id' => $boardId, 'index' => 2],
        ]);
    }
}
