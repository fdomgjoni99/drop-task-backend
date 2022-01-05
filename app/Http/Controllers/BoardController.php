<?php

namespace App\Http\Controllers;

use App\Http\Resources\BoardResource;
use App\Models\Board;
use App\Models\Listing;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index(){
        $boards = Board::select('id','title', 'updated_at')->paginate(20);
        return BoardResource::collection($boards);
    }

    public function store(Request $request){
        $data = $request->validate([
            'title' => 'required|min:5|max:300',
        ]);
        $data = array_merge($data, ['user_id' => auth()->user()->id]);
        $board = Board::create($data);
        $listings = Listing::storeListings($board->id);
        return $board;
    }

    public function destroy($id){
        $board = Board::findOrFail($id);
        $deleted = $board->delete();
        return $board;
    }

    public function show($id){
        $board = Board::findOrFail($id);
        return new BoardResource($board);
    }

    public function update(Request $request, $id){
        $board = Board::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|min:5|max:300'
        ]);
        $board->update($data);
        return new BoardResource($board);
    }
}
