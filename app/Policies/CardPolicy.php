<?php

namespace App\Policies;

use App\Models\Card;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CardPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user, $listingId)
    {
        $boardUserId = Listing::findOrFail($listingId)->board->user_id;
        return $user->id == $boardUserId;
    }

    public function view(User $user, $listingId)
    {
        $boardUserId = Listing::findOrFail($listingId)->board->user_id;
        return $user->id == $boardUserId;
    }

    public function create(User $user, $listingId)
    {
        $boardUserId = Listing::findOrFail($listingId)->board->user_id;
        return $user->id == $boardUserId;
    }

    public function update(User $user, ?Card $card, $listingId)
    {
        $boardUserId = Listing::findOrFail($listingId)->board->user_id;
        return $user->id == $boardUserId;
    }
    
    public function delete(User $user, ?Card $card, $listingId)
    {
        $boardUserId = Listing::findOrFail($listingId)->board->user_id;
        return $user->id == $boardUserId;
    }
}
