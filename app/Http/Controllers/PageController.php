<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Page;
use App\Content;
use Auth;
use DB;

class PageController extends Controller
{

    private function getRules($id=null) 
    {
        return [
            'category_id'   =>  'nullable|integer',
            'user_id'       =>  'bail|required|integer',
            'title'         =>  'required|unique:pages,title,' . $id . '|max:100',
            'summary'       =>  'nullable|max:1048',
            'metakey'       =>  'nullable|max:255',
            'metadesc'      =>  'nullable|max:255',
            'media_url'     =>  'nullable|max:255',
            'access_level'  =>  'required|in:F,M,P|max:1',

            'body'          =>  'required',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::all();

        return response()->json([
            'length' => count($pages),
            'data' => $pages
        ]);
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
        // AUTH User Id
        $request->request->add(['user_id' => Auth::id()]);
        // validate
        $request->validate($this->getRules());

        $page = null;
        DB::transaction(function() use ($request, &$page) {
            $input = $request->input();
            $page = Page::create($input);

            $content = new Content($input);
            $page->contents()->save($content);
        });
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
        $page = Page::with('contents')->FindOrFail($id);
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
        $page = Page::with('contents')->FindOrFail($id);
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
        // AUTH User Id
        $request->request->add(['user_id' => Auth::id()]);
        // validate
        $request->validate($this->getRules($id));

        $page = null;
        DB::transaction(function() use ($request, $id, &$page) {
            $input = $request->input();
            $page = Page::FindOrFail($id);
            $page->fill($input)->save();

            DB::table('contents')->where('page_id', $id)->update(['body' => $input['body']]);
        });
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
        DB::transaction(function() use ($id) {
            DB::table('contents')->where('page_id', $id)->delete();

            $page = Page::FindOrFail($id);
            $page->delete();
        });
        return response()->json(null, 204); 
    }
}
