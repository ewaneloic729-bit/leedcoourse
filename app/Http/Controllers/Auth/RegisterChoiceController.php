<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class RegisterChoiceController extends Controller
{
    public function create()
    {
        return view('auth.register-choice');
    }
}
