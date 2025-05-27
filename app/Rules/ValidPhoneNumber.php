<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidPhoneNumber implements Rule
{
    public function passes($attribute, $value)
    {
        return preg_match('/^(0|\+84)\d{9,10}$/', $value);
    }

    public function message()
    {
        return 'Số điện thoại không hợp lệ.';
    }
}


