<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Page;
use \Cache;

class CategoryController extends Controller
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
            'name' => 'bail|required|unique:categories,name,' . $id . '|max:60',
            'description' => 'nullable|max:255',
            'parent_id' => 'nullable|integer',
            'access_level' => 'in:F,M,P|max:1',
        ];
    }

    /**
     * Display the Single Page Application view for category.
     * This route is accessible via web, whereas all the
     * other routes are only accessible via API.
     */
    public function adminHome()
    {
        return view('admin.categories.home');
    }

    private function form($id = null)
    {
        return view('admin.categories.form', compact('id'));
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
        $categories = Category::all();

        return response()->json([
                'length' => count($categories),
                'data' => $categories
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
        // validate
        $request->validate($this->getRules());

        $input = $request->input();
        $category = Category::create($input);
        //$this->revalidateMenu();

        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::FindOrFail($id);

        return response()->json($category);
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
        $request->validate($this->getRules($id));

        $input = $request->input();
        $category = Category::FindOrFail($id);
        $category->fill($input)->save();
        //$this->revalidateMenu();

        return response()->json($category, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::FindOrFail($id);
        $category->delete();

        return response()->json($category->name);
    }

    /**
     * Show the tags for a category.
     *
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function tags($id)
    {
        $category = Category::FindOrFail($id);
        $tags = $category->tags()->get();
        
        return response()->json([
            'length' => count($tags),
            'data' => $tags
        ]);
    }

    /**
     * Show the comments for a category.
     *
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function comments($id)
    {
        $category = Category::FindOrFail($id);
        $comments = $category->comments()->get();

        return response()->json([
            'length' => count($comments),
            'data' => $comments
        ]);
    }

    /**
     * Display a listing of recent pages under a category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function pages(Request $request, $id)
    {
        $input = $request->input();
        $limit = isset($input['n']) ? $input['n'] : 5;
        $category = Category::where('id', $id)->first();
        if (isset($category)) {
            $pages = Page::with('category', 'tags')
                        ->where([['category_id', $category->id],['publish', 'Y']])
                        ->latest('updated_at')
                        ->take($limit)
                        ->get();
            
            return response()->json([
                'length' => count($pages),
                'data' => $pages
            ]);
        }
        else {
            $pages = Page::with('category', 'tags')
                        ->where('publish', 'Y')
                        ->latest('updated_at')
                        ->take($limit)
                        ->get();
            
            return response()->json([
                'length' => count($pages),
                'data' => $pages
            ]);
        }
    }

    /**
     * Revalidate the category menu cache.
     *
     */
    private function revalidateMenu()
    {
        return Cache::rememberForever('menu', function () {
            return DB::table('categories')->whereNull('parent_id')->pluck('id', 'name');
        });
    }
}
