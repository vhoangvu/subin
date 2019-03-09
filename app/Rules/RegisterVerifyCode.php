<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RegisterVerifyCode implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        return $value == env('REGISTER_VERIFY_CODE', '14buga');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.register_verify_code');
    }
}
