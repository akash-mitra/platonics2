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
            'category_id' => 'required|integer',
            'user_id' => 'bail|required|integer|exists:users,id',
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
     * Returns a view to show the listing of all
     * the pages.
     */
    public function adminHome()
    {
        return view('admin.pages.home');
    }

    /**
     * Returns a view to be used by both create and
     * edit purpose for a single page.
     */
    private function form($id = null)
    {
        return view('admin.pages.form', compact('id'));
    }

    /**
     * Returns an empty view for single page
     * creation purpose
     */
    public function create()
    {
        return $this->form();
    }

    /**
     * Returns a view for a single page
     * edit purpose
     */
    public function edit($page)
    {
        return $this->form($page);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::with('users', 'category', 'tags')->get();

        return response()->json([
            'length' => count($pages),
            'data' => $pages->each->append(['slug','metrics'])
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
        $request->request->add(['user_id' => Auth::id()]);

        $this->validateUnlessDraft($request);

        $input = $request->input();
        $page = new Page($input);
        $content = new Content($input);

        DB::transaction(function () use ($page, $content) {
            $page->save();
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
        $page = Page::FindOrFail($id);

        return response()->json($page->append(['slug','metrics']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->request->add(['user_id' => Auth::id()]);

        $this->validateUnlessDraft($request, $id);

        $input = $request->input();
        $page = Page::FindOrFail($id);

        DB::transaction(function () use ($page, $input) {
            $page->fill($input)->save();
            $page->contents()->update(['body' => $input['body']]);
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
     * Show the tags for a page.
     *
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function tags($id)
    {
        $page = Page::FindOrFail($id);
        $tags = $page->tags()->get();

        return response()->json([
            'length' => count($tags),
            'data' => $tags
        ]);
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

    /**
     * valdate only when the page is not being
     * auto-saved as a draft.
     */
    private function validateUnlessDraft($request, $id = null)
    {
        if ($request->input('draft') != 'Y') {
            $request->validate($this->getRules($id));
        }
    }
}
