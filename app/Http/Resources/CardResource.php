<?php

namespace App\Http\Resources;

use Hashids\Hashids;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => (new Hashids(env('HASHIDS_SALT'), 8))->encode($this->id),
            'description' => $this->description,
            'checklist' => $this->checklist,
            'listing_id' => (new Hashids(env('HASHIDS_SALT'), 8))->encode($this->listing_id),
        ];
    }
}
