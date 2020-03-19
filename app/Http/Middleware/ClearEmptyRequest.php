<?php

namespace App\Http\Middleware;

use Closure;

class ClearEmptyRequest
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
//        $all = array_filter($request->json()->all());
//        if (count($all) == count($all, 1)) {
//            collect($all)->map(function ($item, $key) use ($request) {
//                if ($item === null && $item === '') {
//                    $request->attributes->remove($key);//add([$key => $item]);
//                }
//            });
//        }
        return $next($request);
    }
}
