<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;

class MemberController extends Controller
{
    public function dashboard()
    {
        return view('member.dashboard');
    }
}
