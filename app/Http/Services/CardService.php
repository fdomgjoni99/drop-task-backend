<?php

namespace App\Http\Services;

use App\Models\Listing;

class CardService
{
    public static function resetIndexesAfterAction($listingId){
        $cards = Listing::find($listingId)->cards()->orderBy('index')->orderBy('updated_at', 'desc')->get();
        $index = 0;
        foreach($cards as $card){
            $card->index = $index++;
            $card->save();
        }
    }
}
