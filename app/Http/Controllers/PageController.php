<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Page;
use App\Content;

class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::all();
        return response()->json($pages);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('page.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate
        $rules = array(
            'category_id'   =>  'nullable|integer',
            'user_id'       =>  'bail|required|integer',
            'title'         =>  'required|unique:pages|max:100',
            'summary'       =>  'nullable|max:1048',
            'metakey'       =>  'nullable|max:255',
            'metadesc'      =>  'nullable|max:255',
            'media_url'     =>  'nullable|max:255',
            'access_level'  =>  'in:F,M,P|max:1',

            'body'          =>  'required',
        );
        $request->validate($rules);

        $input = $request->input();
        $page = Page::create($input);

        $content = new Content($input);
        $page->contents()->save($content);

        return response()->json($page, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page = Page::FindOrFail($id);
        return response()->json($page);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page = Page::FindOrFail($id);
        return view ('page.edit', compact('page'));
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
        $input = $request->input();      
        $page = Page::FindOrFail($id);
        $page->fill($input)->save();

        $content = $page->contents;
        $content->fill($input);
        $page->contents()->save($content);

        return response()->json($page, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = Page::FindOrFail($id);
        $page->delete();
        return response()->json(null, 204); 
    }
}
