<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Page;

class UserController extends Controller
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6', //|confirmed
            'type' => 'required|in:Admin,Editor,Author,Regular',
        ];
    }

    /**
     * Display the Single Page Application view for user.
     * This route is accessible via web, whereas all the
     * other routes are only accessible via API.
     */
    public function adminHome()
    {
        return view('admin.users.home');
    }

    private function form ($id = null)
    {
        return view('admin.users.form', compact('id'));
    }

    public function create()
    {
        return $this->form();
    }

    public function edit($user) 
    {
        return $this->form($user);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all()->makeVisible(['id', 'email']);

        return response()->json([
                'length' => count($users),
                'data' => $users
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
        $input['slug'] = uniqid(mt_rand(), true);
        $user = User::create($input);

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::FindOrFail($id)->makeVisible(['id', 'email']);

        return response()->json($user);
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
        $request->validate($this->getRules());

        $input = $request->input();
        $user = User::FindOrFail($id);
        $user->fill($input)->save();

        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function type(Request $request, $id)
    {
        $input = $request->input();
        $types = ['Admin','Editor','Author','Regular'];
        if (!in_array($input['type'], $types))
            return response()->json('Invalid User Type', 302);
        
        $user = User::FindOrFail($id);
        $user->type = $input['type'];
        $user->save();

        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::FindOrFail($id);
        $user->delete();
        
        return response()->json($user->name);
    }

    /**
     * Display a listing of recent pages under a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string                    $slug
     * @return \Illuminate\Http\Response
     */
    public function pages(Request $request, $slug)
    {
        $input = $request->input();
        $limit = isset($input['n']) ? $input['n'] : 5;
        $user = User::where('slug', $slug)->first();
        if (isset($user)) {
            $pages = Page::with('category', 'tags')
                        ->where([['user_id', $user->id],['publish', 'Y']])
                        ->latest('updated_at')
                        ->take($limit)
                        ->get();
            
            return response()->json([
                'length' => count($pages),
                'data' => $pages
            ]);
        }
        else {
            return response()->json([]);
        }
    }

    /**
     * Display a listing of draft pages under a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string                    $slug
     * @return \Illuminate\Http\Response
     */
    public function draft(Request $request, $slug)
    {
        $input = $request->input();
        $limit = isset($input['n']) ? $input['n'] : 5;
        $user = User::where('slug', $slug)->first();
        if (isset($user)) {
            $pages = Page::with('category', 'tags')
                        ->where([['user_id', $user->id], ['draft', 'Y']])
                        ->latest('updated_at')
                        ->take($limit)
                        ->get();
            
            return response()->json([
                'length' => count($pages),
                'data' => $pages
            ]);
        }
        else {
            return response()->json([]);
        }
    }
}
