<?php

namespace App\Http;

use Illuminate\Support\Arr;

class Request extends \Illuminate\Http\Request
{
    function has($key)
    {
        $keys = is_array($key) ? $key : func_get_args();

        $input = $this->all();

        foreach ($keys as $value) {
            if (!Arr::has($input, $value)) {
                return false;
            }
        }

        return true;
    }
}
