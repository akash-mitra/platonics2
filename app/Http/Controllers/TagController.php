<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Tag;

class TagController extends Controller
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
            'user_id' => 'bail|required|integer|exists:users,id',
            'name' => 'required|unique:tags,name,' . $id . '|max:60',
            'description' => 'required|max:255',
        ];
    }

    /**
     * Display the Single Page Application view for tag.
     * This route is accessible via web, whereas all the
     * other routes are only accessible via API.
     */
    public function adminHome()
    {
        return view('admin.tags.home');
    }

    private function form($id = null)
    {
        return view('admin.tags.form', compact('id'));
    }

    public function create()
    {
        return $this->form();
    }

    public function edit($tag)
    {
        return $this->form($tag);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();

        return response()->json([
            'length' => count($tags),
            'data' => $tags
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

        $request->validate($this->getRules());

        $input = $request->input();
        $tag = Tag::create($input);

        return response()->json($tag, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tag = Tag::FindOrFail($id);

        return response()->json($tag);
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
        $request->request->add(['user_id' => Auth::id()]);
        
        $request->validate($this->getRules($id));

        $input = $request->input();
        $tag = Tag::FindOrFail($id);
        $tag->fill($input)->save();

        return response()->json($tag, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::FindOrFail($id);
        $tag->delete();

        return response()->json($tag->name);
    }

    /**
     * Attach a taggable object to a tag.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function attach(Request $request)
    {
        $user_id = Auth::id();

        $input = $request->input();
        $tags = $input['tags'];
        foreach ($tags as $id) {
            $tag = Tag::FindOrFail($id);
            $tag->taggables($input['taggable_type'])->attach([$input['taggable_id'] => ['user_id' => $user_id]]);
        }
        return response()->json($tag, 201);
    }

    /**
     * Detach a taggable object from a tag.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function detach(Request $request)
    {
        $input = $request->input();
        $tag = Tag::FindOrFail($input['tag_id']);
        $tag->taggables($input['taggable_type'])->detach($input['taggable_id']);
        
        return response()->json($tag, 200);
    }

    /**
     * Display the categories under a tag resource.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function categories($name)
    {
        $tag = Tag::with('categories')->where('name', $name)->firstOrFail();
        $relations = $tag->getRelations();
        $categories = $relations['categories'];

        return response()->json([
            'length' => count($categories),
            'data' => $categories
        ]);
    }

    /**
     * Display the pages under a tag resource.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function pages($name)
    {
        $tag = Tag::with('pages')->where('name', $name)->firstOrFail();
        $relations = $tag->getRelations();
        $pages = $relations['pages'];

        return response()->json([
            'length' => count($pages),
            'data' => $pages
        ]);
    }

    /**
     * Display the taggables under a tag name.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function all($name)
    {
        $tag = Tag::with('categories', 'pages')->where('name', $name)->firstOrFail();
        $relations = $tag->getRelations();
        $categories = $relations['categories'];
        $pages = $relations['pages'];

        return response()->json([
            'length' => count($relations),
            'data' => [
                'categories' => [ 
                    'length' => count($categories),
                    'data' => $categories
                ],
                'pages' => [ 
                    'length' => count($pages),
                    'data' => $pages
                ]
            ]
        ]);
    }
}
