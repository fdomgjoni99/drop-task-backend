<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCardRequest;
use App\Http\Services\CardService;
use App\Models\Card;
use App\Models\ChecklistItem;
use App\Models\Listing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
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
        $listingId = $request->query('listing_id');
        $this->authorize('viewAny', [Card::class, $listingId]);
        $request->validate([
            'listing_id' => 'required|exists:listings,id'
        ]);
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
            'title' => 'required|min:3|max:200',
            'description' => 'max:2000',
        ]);
        $this->authorize('create', [Card::class, $data['listing_id']]);
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
        $card = Card::with('checklistItems')->findOrFail($id);
        $card->progress = ChecklistItem::getProgress($card->id);
        $this->authorize('view', [Card::class, $card->listing_id]);
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
        $this->authorize('update', [$card, $data['listing_id']]);
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
            DB::beginTransaction();
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
            DB::commit();
            return $card;
        }
        catch(AuthorizationException $e){
            return response()->json(['message' => 'Forbidden!'], 403);
        }
        catch(Exception $e){
            DB::rollBack();
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
            DB::beginTransaction();
            $card = Card::findOrFail($id);
            $this->authorize('delete', [$card, $card->listing_id]);
            $card->delete();
            CardService::resetIndexesAfterAction($card->listing_id);
            DB::commit();
            return $card;
        }
        catch(AuthorizationException $e){
            return response()->json(['message' => 'Forbidden!'], 403);
        }
        catch(Exception $e){
            DB::rollBack();
            return ['message' => 'Something went wrong, card could not be deleted!'];
        }
    }
}
