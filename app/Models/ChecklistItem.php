<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
    protected $casts = [
        'completed' => 'boolean'
    ];

    public function card(){
        return $this->belongsTo(Card::class);
    }

    public function setTextAttribute($value){
        $this->attributes['text'] = ucfirst($value);
    }

    public static function getProgress($cardId){
        $totalCount = static::query()->where([
            ['card_id', $cardId],
        ])->count();
        if($totalCount == 0)
            return null;
        $completedCount = static::query()->where([
            ['card_id', $cardId],
            ['completed', true]
        ])->count();
        return $completedCount . '/' . $totalCount;
    }
}
