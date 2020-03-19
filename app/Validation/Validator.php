<?php

namespace App\Validation;

use Illuminate\Support\Facades\Input;

class Validator extends \Illuminate\Validation\Validator
{
    /**
     * Run the validator's rules against its data.
     *
     * @return array
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate()
    {
        if ($this->fails()) {
            $validationException = new \Illuminate\Validation\ValidationException($this);
//            $validationException = new ValidationException($this);
            $validationException->status(200);
            throw $validationException;
        }

        return $this->validated();
    }

    function validateMobile($value, $parameters, $other)
    {
        $tmp = Input::get($other[0]);
        return false;
    }
}

