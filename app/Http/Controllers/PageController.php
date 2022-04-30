<?php

namespace App\Http\Controllers;

use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return PageResource::collection(Page::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'content' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = $request->all();
        $page = Page::query()->create($data);

        return $this->sendResponse($page, 'Page create successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return PageResource
     */
    public function show($id)
    {
        $page = Page::query()->find($id);
        if(!$page) {
            return $this->sendError('Not found');
        }
        Page::query()->find($id)->update([
            'views' => DB::raw('views+1'),
        ]);
        return new PageResource($page);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'content' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = $request->all();
        $page = Page::query()->findOrFail($id)->update($data);

        return $this->sendResponse($page, 'Page update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Page $page
     * @return Response
     */
    public function destroy($id)
    {
        $page = Page::destroy($id);
        return $this->sendResponse($page, 'Page delete successfully.');
    }
}
