<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @group 用户000
 * @package App\Http\Controllers
 */
class ApiTokenController extends Controller
{
//    use AuthenticatesUsers;
    /**
     * 注册00001
     * @bodyParam name required string 登录名
     * @bodyParam email required string email
     * @bodyParam password required string 密码
     * @bodyParam repassword required string 重复密码
     * @param Request $request
     * @return User|\Illuminate\Database\Eloquent\Model
     */
    function store(Request $request)
    {
        /** @noinspection PhpUndefinedClassInspection */
        return User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => \Hash::make($request->input('password')),
            'api_token' => Str::random(60),
        ]);
    }

    /**
     * 更新令牌 00002
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function update(Request $request)
    {
        /** @noinspection PhpUndefinedClassInspection */
        $token = Str::random(60);

        $request->user()->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();

        return ['token' => $token];
    }

    /**
     * 登出 00003
     * @response {
     *
     * }
     * @param Request $request
     * @return array
     */
    function logout(Request $request)
    {
        $user = $request->user();
        $user->api_token = null;
        $user->save();
        return [];
    }

    /**
     * 登录 00004
     * @bodyParam name required 登录名
     * @bodyParam password required 登录密码
     * @response {
     *  "api_token":"$2y$10$gvFOvewIb/nh5rQ5dTCnNuN1ofdaSz0wJu1jSF8qIGdYZB5E/GwZq"
     * }
     * @param Request $request
     * @return User
     * @throws \Exception
     */
    function login(Request $request)
    {
        /** @var User $user */
        $user = (new User())->newQuery()->where('name', $request->input('name'))->first();
        $bool = (new BcryptHasher())->check($request->input('password'), $user->password);
        if (!$bool) {
            throw new \Exception("非法用户");
        }

        if (empty($user->api_token)) {
            $user->api_token = \Hash::make($request->input('password'));
            $user->save();
        }
        return $user;
    }
}
