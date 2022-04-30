<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsAllResource;
use App\Http\Resources\NewsCollection;
use App\Http\Resources\NewsResource;
use App\Http\Resources\UserResource;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Date\Date;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return NewsCollection
     */
    public function index(Request $request)
    {

        $news = News::query();

        //Если не админ
        if (!Auth::guard('api')->check()) {
            $news->where('published_at', '<', Carbon::now()); //Отсеиваем будущие новости
        }

        //Если фильтрация
        if ($request->date) {
            $dates = collect(explode("/", $request->date));
            $news->whereBetween('published_at', [$dates->first(), $dates->last()]);
        }

        $news = $news->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(6);

        //return NewsResource::collection($news);
        return new NewsCollection($news);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'preview' => 'required',
            'content' => 'required',
            'published_at' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = $request->all();
        $data['published_at'] = Date::parse($data['published_at'])->format('Y-m-d');
        $news = News::query()->create($data);

        return $this->sendResponse($news, 'News create successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return NewsResource
     */
    public function show($id)
    {
        $news = News::query()->find($id);
        if(!$news) {
            return $this->sendError('Not found');
        }
        News::query()->find($id)->update([
            'views' => DB::raw('views+1'),
        ]);
        return new NewsResource($news);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|max:255',
            'preview' => 'sometimes|required|max:500',
            'content' => 'sometimes|required',
            'published_at' => 'sometimes|required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = $request->all();
        $news = News::query()->findOrFail($id)->update($data);

        return $this->sendResponse($news, 'News update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $news = News::destroy($id);
        return $this->sendResponse($news, 'News delete successfully.');
    }

    public function restore($id)
    {
        $news = News::withTrashed()->find($id)->restore();
        return $this->sendResponse($news, 'News restore successfully.');
    }
}
