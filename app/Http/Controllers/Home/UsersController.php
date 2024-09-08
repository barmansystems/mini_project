<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        if (!request()->has('company') || empty(request()->input('company'))) {

            return redirect()->route('users.index', ['company' => 'parsoTejarat']);
        }
        $users = User::query();

        if (request('company') == "parsoTejarat") {
            $users->where('company_name', 'parso');
        }

        if (request('company') == "adakTejarat") {
            $users->where('company_name', 'adaktejarat');
        }
        if (request('company') == "adakHamrah") {
            $users->where('company_name', 'adakhamrah');
        }

        $users = $users->latest()->paginate(30);

        return view('users.index', compact(['users']));
    }
}
