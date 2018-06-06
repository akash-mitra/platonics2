<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Category;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
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
    public function home()
    {
        return view('admin.categories');
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
        // validate
        $request->validate($this->getRules($id));

        $input = $request->input();
        $category = Category::FindOrFail($id);
        $category->fill($input)->save();
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
}
