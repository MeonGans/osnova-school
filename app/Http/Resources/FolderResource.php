<?php

namespace App\Http\Resources;

use App\Http\Controllers\FolderController;
use App\Http\Controllers\MenuController;
use Illuminate\Http\Resources\Json\JsonResource;

class FolderResource extends JsonResource
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
            'type' => 'folder',
            'name' => $this->name,
            'order' => $this->order,
            'items' => MenuController::nav($this->id),
        ];
    }
}
