<?php

namespace App\Http\Middleware;

use DB;
use Auth;
use Closure;
use App\Permission;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

class CheckPermission
{
    private $type;
    private $action;
    private $resource;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $method = explode('.', Route::currentRouteName());

        $this->type = Auth::guest() ? 'Visitor' : Auth::user()->type;

        $this->action = $method[0] == 'admin' ? $method[0] : $method[1];

        $this->resource = $method[0] == 'admin' ? $method[1] : $method[0];

        if ($this->checkAcl()) {
            return $next($request);
        }

        if ($request->ajax()) {
            return response()->json(['status' => 'You do not have permission'], 302);
        } else {
            return back()->with('status', 'You do not have permission');
        }
    }

    /**
     * Check allow in AccessControlList.
     */
    private function checkAcl()
    {
        $acl = Cache::rememberForever('permissions', function () {
            return DB::table('permissions')->get();
        });

        $permission = $acl->where('type', $this->type)->where('resource', $this->resource)->where('action', $this->action)->pluck('permission')->first();

        return $permission == 'allow' ? true : false;
    }
}
