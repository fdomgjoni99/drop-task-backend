<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCardRequest;
use App\Models\Card;
use App\Models\Listing;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($listingId)
    {
        $cards = Card::where('listing_id', $listingId)->get();
        return $cards;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCardRequest $request, $listingId)
    {
        $data = $request->validated();
        $progress = collect($data['checklist'])->countBy('completed');
        $data['checklist']['progress'] = $progress[1] . '/' . $progress[0];
        $data['checklist'] = json_encode($data['checklist']);
        $listing = Listing::findOrFail($listingId);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($listingId, $id)
    {
        $card = Card::findOrFail($id);
        $card->delete();
        return $card;
    }

    public function trashedCards(){
        $cards = Card::onlyTrashed()->get();
        return $cards;
    }

    public function destroyPermanently($listingId, $id){
        $card = Card::onlyTrashed()->find($id);
        if(!$card)
            return response()->json([], 404);
        $card->forceDelete();
        return $card;
    }

    public function add(){
        
    }

    public function remove(){
        
    }

    public function move(){
        
    }
}
