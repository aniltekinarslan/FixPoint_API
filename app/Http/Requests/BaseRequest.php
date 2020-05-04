<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

class BaseRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function all()
    {
        $input = parent::all();
        dd($input);

        return $input;
    }
}
