<?php

namespace App\Http\Controllers;

use App\Http\Resources\FolderResource;
use App\Http\Resources\LinkResource;
use App\Http\Resources\PageResource;
use App\Models\Folder;
use App\Models\Link;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index()
    {
        return FolderResource::collection(Link::all());
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
            'name' => 'required|max:255',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = $request->all();
        $folder = Folder::query()->create($data);

        return $this->sendResponse($folder, 'Folder create successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = $request->all();
        $folder = Folder::query()->findOrFail($id)->update($data);

        return $this->sendResponse($folder, 'Folder update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $folder = Folder::destroy($id);
        return $this->sendResponse($folder, 'Folder delete successfully.');
    }
}
