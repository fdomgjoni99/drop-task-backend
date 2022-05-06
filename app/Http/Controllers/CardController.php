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
            'description' => 'max:2000',
            'previous_index' => 'integer|exists:cards,index',
            'previous_listing_index' => 'integer|exists:listings,index',
            'current_index' => 'integer',
            'current_listing_index' => 'integer',
        ]);
        $card = Card::findOrFail($id);
        $card->update($data);
        return $card;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $card = Card::findOrFail($id);
        $card->delete();
        return $card;
    }

    public function add(){
        
    }

    public function remove(){
        
    }

    public function move(){
        
    }
}
