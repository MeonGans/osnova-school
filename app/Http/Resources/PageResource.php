<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
            'id' => $this->id,
            'type' => $this->when(!$this->content, 'page'),
            'name' => $this->name,
            'content' => $this->when($this->content, $this->content),
            'views' => $this->when($this->views !== null, $this->views),
            'order' => $this->order,
        ];
    }
}
