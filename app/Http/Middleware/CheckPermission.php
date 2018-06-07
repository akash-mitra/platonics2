<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Auth;
use DB;
use App\Permission;

class CheckPermission
{
    private $resource;
    private $action;
    private $type;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $method = explode(".", Route::currentRouteName());
        $this->resource = $method[0] == 'admin' ? $method[1] : $method[0];
        $this->action = $method[0] == 'admin' ? $method[0] : $method[1];
        $this->type = Auth::guest() ? 'Visitor' : Auth::user()->type; 
        
        if($this->checkAcl())
            return $next($request);
        else {
            if($request->ajax())
                return response()->json(['status' => 'You do not have permission'], 302);
            else
                return back()->with('status', 'You do not have permission');
        }
        

    }


    /**
     * Check allow in AccessControlList.
     */
    private function checkAcl()
    {
        //$acl = Permission::all();
        $acl = Cache::remember('permissions', 60, function() {
            return DB::table('permissions')->get();
        });
        $permission = $acl->where('type', $this->type)->where('resource', $this->resource)->where('action', $this->action)->pluck('permission')->first();
        return $permission == "allow" ? true : false;
    }
}
