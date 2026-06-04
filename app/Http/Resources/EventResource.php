<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return[
           'id'            => $this->id,
           'title'         => $this->title,
           'description'   => $this->description,
           'location'      => $this->location,
           'date'          => $this->date,
           'AvalibleSeats' => $this->avalible_seats,
        //    'category_id' => null,
           'category_id'   => new CategoryResource($this->whenLoaded('category')),
           // category return to method in model Event (belong)
           'images'        =>$this->getMedia('event_images')->map(function($media){

                                     return $media->getUrl();

                   }),
        ];
    }
}
