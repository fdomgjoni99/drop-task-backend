<?php

namespace App\Http\Controllers;

use App\Http\Resources\BoardResource;
use App\Models\Board;
use App\Models\Listing;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoardController extends Controller
{
    public function index(){
        $boards = Board::select('id','title', 'updated_at')->paginate(20);
        return $boards;
    }

    public function store(Request $request){
        $data = $request->validate([
            'title' => 'required|min:5|max:300',
        ]);
        $data = array_merge($data, ['user_id' => auth()->user()->id]);
        $board = null;
        try{
            DB::transaction(function() use($data, &$board){
                $board = Board::create($data);
                Listing::storeListings($board->id);
            });
            if($board)
                return $board;
        }catch(Exception $e){
            return ['message' => 'error'];
        }
    }

    public function destroy($id){
        $board = Board::findOrFail($id);
        $board->delete();
        return $board;
    }

    public function show($id){
        $board = Board::findOrFail($id);
        return $board;
    }

    public function update(Request $request, $id){
        $board = Board::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|min:5|max:300'
        ]);
        $board->update($data);
        return $board;
    }
}
