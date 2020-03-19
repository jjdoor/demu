<?php

namespace App\Http\Middleware;

use App\User;
use App\Validation\ValidatesRequests;
use Closure;
use Illuminate\Support\Facades\Config;

//extends Middleware
class ContractRoleCheck
{
    use ValidatesRequests;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $messages = [
            'virtual.role_check' => '你没有对合同模块操作的权限'
        ];
        $rules = [
            'virtual' => 'role_check',
        ];
        \Illuminate\Support\Facades\Validator::extend('role_check', function ($attribute, $value) use ($request) {
            $user = $request->user();
            $role = User::query()->with(['roles'])->where('id', $user->id)->first();
            if (isset($role->roles[0])) {
                $role_new = $role->roles[0];
                $login_role_id = data_get($role_new, 'id', null);
            } else {
                $login_role_id = null;
            }
            if (isset(array_flip(Config::get('constants.REVIEW_ALIAS'))[$login_role_id])) {
                return true;
            }
            return false;
        });
        $this->validate($request, $rules, $messages);

        return $next($request);
    }
}
