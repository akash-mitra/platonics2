<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

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
            'type' => 'required|in:Admin,Editor,Author,Regular',
        ];
    }

    /**
     * Display the Single Page Application view for user.
     * This route is accessible via web, whereas all the
     * other routes are only accessible via API.
     */
    public function home()
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
        $users = User::all();
        return response()->json([
                'length' => count($users),
                'data' => $users
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::FindOrFail($id);
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
        // validate
        $request->validate($this->getRules());

        $input = $request->input();
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
}
