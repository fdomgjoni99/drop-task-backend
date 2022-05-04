<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\Card;
use App\Models\Listing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Models\User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => Hash::make('admin')
        ]);
        $boards = Board::insert([
            [ 'title' => 'Hello World', 'user_id' => $user->id ],
            [ 'title' => 'Hello World 2', 'user_id' => $user->id ],
        ]);
        $listings = Listing::insert([
            ['type' => 0, 'board_id' => 1],
            ['type' => 1, 'board_id' => 1],
            ['type' => 2, 'board_id' => 1],
        ]);
        $cards = Card::insert([
            ['title' => 'This is my first card', 'listing_id' => 1, 'index' => 0, 'description' => 'This is an amazing description']
        ]);
    }
}
