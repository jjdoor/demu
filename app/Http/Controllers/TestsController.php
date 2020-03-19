<?php

namespace App\Http\Controllers;

use App\Port;
use Illuminate\Http\Request;

class TestsController extends BaseController
{

    //
    function show(Request $request)
    {
        $port = Port::query()->where('id', 1)->with(['segment_businesses', 'master_businesses', 'slaver_businesses'])->first();

        return $port;
    }
}
