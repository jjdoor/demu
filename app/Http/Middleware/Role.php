<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        /** @var User $user */
//        $request->attributes->add(['user_id' => $user->id]);
        $request->attributes->add(['login_user_id' => $user->id]);
        $request->attributes->add(['login_user_name' => $user->name]);
        $role = User::query()->with(['roles'])->where('id', $user->id)->first();
        if (isset($role->roles[0])) {
            $role_new = $role->roles[0];
//            $request->attributes->add(['role_id' => data_get($role_new, 'id', null)]);
//            $request->attributes->add(['role_name' => data_get($role_new, 'name', null)]);
            $request->attributes->add(['login_role_id' => data_get($role_new, 'id', null)]);
            $request->attributes->add(['login_role_name' => data_get($role_new, 'name', null)]);
        } else {
            $request->attributes->add(['login_role_id' => null]);
            $request->attributes->add(['login_role_name' => null]);
        }
//        $request->attributes->add(['role'=>$role]);
        return $next($request);
    }
}
