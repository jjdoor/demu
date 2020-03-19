<?php

namespace App\Validation;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait ValidatesRequests
{
    /**
     * Run the validation routine against the given validator.
     *
     * @param  \Illuminate\Contracts\Validation\Validator|array $validator
     * @param  \Illuminate\Http\Request|null $request
     * @return array
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateWith($validator, Request $request = null)
    {
        $request = $request ?: request();

        if (is_array($validator)) {
            $validator = $this->getValidationFactory()->make($request->all(), $validator);
        }

        return $validator->validate();
    }

    /**
     * Get a validation factory instance.
     *
     * @return \Illuminate\Contracts\Validation\Factory
     */
    protected function getValidationFactory()
    {
        return app(Factory::class);
    }

    /**
     * Validate the given request with the given rules.
     *
     * @param  string $errorBag
     * @param  \Illuminate\Http\Request $request
     * @param  array $rules
     * @param  array $messages
     * @param  array $customAttributes
     * @return array
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateWithBag($errorBag, Request $request, array $rules,
                                    array $messages = [], array $customAttributes = [])
    {
        try {
            return $this->validate($request, $rules, $messages, $customAttributes);
        } catch (ValidationException $e) {
            $e->errorBag = $errorBag;

            throw $e;
        }
    }

    /**
     * Validate the given request with the given rules.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  array $rules
     * @param  array $messages
     * @param  array $customAttributes
     * @return array
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate(Request $request, array $rules,
                             array $messages = [], array $customAttributes = [])
    {
        $data = array_filter(array_merge($request->json()->all(), $request->all(), ['virtual' => 1]), function ($var) {
            if ($var !== null) {
                return true;
            }
        });
        $factory = $this->getValidationFactory();
        $factory->resolver(function ($translator, $data, $rules, $messages, $customAttributes) {
//            return new \Illuminate\Validation\Validator($translator, $data, $rules, $messages, $customAttributes);
            return new Validator($translator, $data, $rules, $messages, $customAttributes);
        });
        $validator = $factory->make(
            $data, $rules, $messages, $customAttributes
        );
        return $validator->validate();
    }

    public function set_validate($request, array $rules,
                                 array $messages = [], array $customAttributes = [])
    {
        if (is_array($request)) {
            $data = $request;
        } else {
            /** @var Request $request */
            $data = $request->toArray();
        }
        $factory = $this->getValidationFactory();
        $factory->resolver(function ($translator, $data, $rules, $messages, $customAttributes) {
//            return new \Illuminate\Validation\Validator($translator, $data, $rules, $messages, $customAttributes);
            return new Validator($translator, $data, $rules, $messages, $customAttributes);
        });
        $validator = $factory->make(
            $data, $rules, $messages, $customAttributes
        );
        return $validator;
    }
}
