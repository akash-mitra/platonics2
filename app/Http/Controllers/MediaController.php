<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Media;
use Auth;

class MediaController extends Controller
{
    private function getRules() 
    {
        return [
            'user_id'       =>  'bail|required|integer|exists:users,id',
            'base_path'     =>  'required|max:255',
            'filename'      =>  'required|max:30',
            'name'          =>  'required|max:60',
            'type'          =>  'required|max:10',
            'size'          =>  'nullable|integer',
            'optimized'     =>  'in:Y,N|max:1',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medias = Media::all();
        
        return response()->json([
            'length' => count($medias),
            'data' => $medias
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // AUTH User Id
        $request->request->add(['user_id' => Auth::id()]);
        // Optimized
        $request->request->add(['optimized' => 'N']);
        // validate
        $request->validate($this->getRules());

        $input = $request->input();
        $media = Media::create($input);
        return response()->json($media, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $media = Media::FindOrFail($id);
        return response()->json($media);
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
        // AUTH User Id
        $request->request->add(['user_id' => Auth::id()]);
        // validate
        $request->validate($this->getRules());

        $input = $request->input();      
        $media = Media::FindOrFail($id);
        $media->fill($input)->save();
        return response()->json($media, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $media = Media::FindOrFail($id);
        $media->delete();
        return response()->json(null, 204); 
    }
}
