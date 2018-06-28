<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Page;
use App\Content;

class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function __construct()
    {
        $this->middleware('permissions');
    }

    private function getRules($id = null)
    {
        return [
            'category_id' => 'bail|required|integer',
            'user_id' => 'required|integer|exists:users,id',
            'title' => 'required|unique:pages,title,' . $id . '|max:100',
            'summary' => 'nullable|max:1048',
            'metakey' => 'nullable|max:255',
            'metadesc' => 'nullable|max:255',
            'media_url' => 'nullable|max:255',
            'access_level' => 'in:F,M,P|max:1',

            'body' => 'required',
        ];
    }

    /**
     * Display the Single Page Application view for page.
     * This route is accessible via web, whereas all the
     * other routes are only accessible via API.
     */
    public function home()
    {
        return view('admin.pages.home');
    }

    private function form($id = null)
    {
        return view('admin.pages.form', compact('id'));
    }

    public function create()
    {
        return $this->form();
    }

    public function edit($category)
    {
        return $this->form($category);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::with('users', 'categories', 'tags')->get();

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
    // public function create()
    // {
    //     return view('admin.pages.form');
    // }

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
        DB::transaction(function () use ($request, &$page) {
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
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page = Page::with('contents', 'users')->FindOrFail($id);

        return response()->json($page);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     $page = Page::with('contents', 'users')->FindOrFail($id);

    //     return view('page.edit', compact('page'));
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // AUTH User Id
        $request->request->add(['user_id' => Auth::id()]);
        // validate
        $request->validate($this->getRules($id));

        $page = null;
        DB::transaction(function () use ($request, $id, &$page) {
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
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = null;
        DB::transaction(function () use ($id, &$page) {
            DB::table('contents')->where('page_id', $id)->delete();

            $page = Page::FindOrFail($id);
            $page->delete();
        });

        return response()->json($page->title);
    }

    /**
     * Toggle update the publish status of a page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function publish(Request $request, $id)
    {
        $input = $request->input();
        $page = Page::FindOrFail($id);
        $input['publish'] = $page['publish'] == 'Y' ? 'N' : 'Y';
        $page->fill($input)->save();

        return response()->json($page, 200);
    }

    /**
     * Show the comments for a page.
     *
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function comments($id)
    {
        $page = Page::FindOrFail($id);
        $comments = $page->comments()->get();

        return response()->json([
            'length' => count($comments),
            'data' => $comments
        ]);
    }
}
