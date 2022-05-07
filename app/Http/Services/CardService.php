<?php

namespace App\Http\Services;

use App\Models\Listing;

class CardService
{
    public static function resetIndexesAfterDeletion($listingId){
        $cards = Listing::find($listingId)->cards;
        $i = 0;
        foreach($cards as $card){
            $card->index = $i++;
            $card->save();
        }
    }
}
