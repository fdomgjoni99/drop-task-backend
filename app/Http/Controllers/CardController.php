<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCardRequest;
use App\Http\Services\CardService;
use App\Models\Card;
use App\Models\Listing;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id'
        ]);
        $listingId = $request->query('listing_id');
        $cards = Card::where('listing_id', $listingId)->get();
        return $cards;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'title' => 'required|min:10|max:200',
            'description' => 'max:2000',
        ]);
        $listing = Listing::findOrFail($request->listing_id);
        $data['index'] = $listing->cards()->max('index') === null ? 0 : $listing->cards()->max('index') + 1;
        $card = new Card($data);
        $listing->cards()->save($card);
        return $card;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $card = Card::findOrFail($id);
        return $card;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'title' => 'required|min:10|max:200',
            'description' => 'max:2000'
        ]);
        $card = Card::findOrFail($id);
        $card->update($data);
        return $card;
    }

    public function move(Request $request, $id){
        $data = $request->validate([
            // 'previous_index' => 'integer|exists:cards,index',
            'previous_listing_index' => 'integer|exists:listings,index',
            'current_index' => 'required|integer',
            'current_listing_index' => 'required|integer',
        ]);
        try{
            $card = null;
            DB::transaction(function() use(&$card, $data, $id){
                $card = Card::findOrFail($id);
                $boardId = $card->listing->board->id;
                $previousListingId = isset($data['previous_listing_index']) ? Listing::where([['board_id', $boardId], ['index', $data['previous_listing_index']]])->first()->id : null;
                $currentListingId = Listing::where([['board_id', $boardId], ['index', $data['current_listing_index']]])->first()->id;
                $card->update([
                    'index' => $data['current_index'],
                    'listing_id' => $currentListingId,
                ]);
                if($previousListingId)
                    CardService::resetIndexesAfterAction($previousListingId);
                CardService::resetIndexesAfterAction($currentListingId);
            });
            return $card;
        }catch(Exception $e){
            return ['message' => 'error card could not be moved!'];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $card = null;
            DB::transaction(function() use(&$card, $id){
                $card = Card::findOrFail($id);
                $card->delete();
                CardService::resetIndexesAfterAction($card->listing_id);
            });
            return $card;
        }catch(Exception $e){
            return ['message' => 'card could not be deleted!'];
        }
    }
}
