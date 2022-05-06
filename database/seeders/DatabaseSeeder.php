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
            'password' => Hash::make('password')
        ]);
        $boards = Board::insert([
            ['title' => 'Example board', 'user_id' => $user->id],
            ['title' => 'Example board 2', 'user_id' => $user->id],
            ['title' => 'Example board 3', 'user_id' => $user->id],
        ]);
        $listings = Listing::insert([
            ['type' => 'Todo', 'board_id' => 1, 'index' => 0],
            ['type' => 'In progress', 'board_id' => 1, 'index' => 1],
            ['type' => 'Done', 'board_id' => 1, 'index' => 2],
        ]);
        $cards = Card::insert([
            ['title' => 'This is my first card', 'listing_id' => 1, 'index' => 0, 'description' => 'This is an amazing description'],
            ['title' => 'This is my second card', 'listing_id' => 1, 'index' => 1, 'description' => 'This is anoyhrt amazing description'],
            ['title' => 'This is my third card', 'listing_id' => 1, 'index' => 2, 'description' => 'This is an amazing description'],
            ['title' => 'This is another card', 'listing_id' => 2, 'index' => 0, 'description' => 'This is an amazing description'],
            ['title' => 'This is a cool card', 'listing_id' => 2, 'index' => 1, 'description' => 'This is an amazing description'],
            ['title' => 'This is an even cooler card', 'listing_id' => 2, 'index' => 2, 'description' => 'This is an amazing description'],
            ['title' => 'This is the latest card', 'listing_id' => 3, 'index' => 0, 'description' => 'This is an amazing description'],
            ['title' => 'This is madness', 'listing_id' => 3, 'index' => 1, 'description' => 'This is madness'],
            ['title' => 'This is sparta!', 'listing_id' => 3, 'index' => 2, 'description' => 'This is madness'],
        ]);
    }
}
