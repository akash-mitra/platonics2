<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Comment;
use App\Category;
use App\Page;
use Auth;
use DB;

class CommentController extends Controller
{
    private function getRules() 
    {
        return [
            'parent_id'         =>  'nullable|integer|exists:comments,id',
            'user_id'           =>  'bail|required|integer|exists:users,id',
            'commentable_id'    =>  'required|integer',
            'commentable_type'  =>  'required|max:30',
            'body'              =>  'required',
            'vote'              =>  'nullable|integer',
            'offensive_index'   =>  'nullable|integer',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::all();

        return response()->json([
            'length' => count($comments),
            'data' => $comments
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('comment.create');
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

        $input = $request->input();
        $model = null;
        if ($input['commentable_type'] == 'App\Category')
            $model = Category::FindOrFail($input['commentable_id']);
        else
            $model = Page::FindOrFail($input['commentable_id']);

        $comment = $model->comments()->create($input);
        return response()->json($comment, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comment = Comment::FindOrFail($id);
        return response()->json($comment);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $comment = Comment::FindOrFail($id);
        return view ('comment.edit', compact('comment'));
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
        $comment = Comment::FindOrFail($id);
        $comment->fill($input)->save();
        return response()->json($comment, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::FindOrFail($id);
        $comment->delete();
        return response()->json(null, 204); 
    }
}
