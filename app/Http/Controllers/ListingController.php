<?php

namespace App\Http\Controllers;

use App\Http\Resources\CardListingResource;
use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index($boardId){
        $listings = Listing::where('board_id', $boardId)
                    ->limit(3)->with('cards')->get();
        return $listings;
    }

    public function show($boardId, $id){
        $listing = Listing::where('board_id', $boardId)
                    ->where('id', $id)
                    ->first();
        return $listing;
    }

    public function updateRows(){
        $data = [
            [
                'id' => 1,
                'row' => 0
            ],
            [
                'id' => 3,
                'row' => 1
            ],
            [
                'id' => 3,
                'row' => 5
            ]
        ];
        $index = 'id';
        Listing::updateBatch();
    }
}
