<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Hashids\Hashids;

class BoardResource extends JsonResource
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
            // 'id' => (new Hashids(env('HASHIDS_SALT'), 8))->encode($this->id),
            'id' => $this->id,
            'title' => $this->title,
            'updated_at' => $this->updated_at->diffForHumans()
        ];
    }
}
