<?php

namespace App\Http\Resources;

use App\Models\News;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class NewsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $news = News::query();
        if (!Auth::guard('api')->check()) {
            $news->where('published_at', '<', Carbon::now()); //Отсеиваем будущие новости
        }
        $news->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->where('is_pinned', '1');

        return [
        'pinned' => NewsResource::collection($news->get()),
        'data' => $this->collection,
        'links' => [
            'self' => 'link-value',
        ],
    ];
    }
}
