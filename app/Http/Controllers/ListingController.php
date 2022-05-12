<?php

namespace App\Http\Controllers;

use App\Http\Resources\CardListingResource;
use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index(Request $request){
        $request->validate([
            'board_id' => 'required|exists:boards,id'
        ]);
        $boardId = $request->query('board_id');
        $listings = Listing::where('board_id', $boardId)
                        ->with('cards')->get();
        return $listings;
    }

    public function store(Request $request){
        $data = $request->validate([
            'type' => 'required',
            'board_id' => 'required|exists:boards,id'
        ]);
        $listingIndex = Listing::where('board_id', $data['board_id'])->max('index') + 1;
        $data['index'] = $listingIndex;
        $listing = Listing::create($data);
        return $listing;
    }

    public function destroy($id){
        $listing = Listing::findOrFail($id);
        $listing->delete();
        return $listing;
    }

    public function update(Request $request, $id){
        $data = $request->validate([
            'type' => 'required',
            'index' => 'required',
            'board_id' => 'required|exists:boards,id'
        ]);
        $listing = Listing::findOrFail($id);
        $listing->update($data);
        return $listing;
    }

    public function show($boardId, $id){
        $listing = Listing::where('board_id', $boardId)
                    ->where('id', $id)
                    ->first();
        return $listing;
    }
}
