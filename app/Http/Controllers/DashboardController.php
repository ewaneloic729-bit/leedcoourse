<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $routeName = $user->dashboardRouteName();

        if ($routeName === 'dashboard') {
            return view('dashboard');
        }

        return redirect()->route($routeName);
    }

    public function eleve()
    {
        return view('dashboards.eleve');
    }

    public function enseignant()
    {
        return view('dashboards.enseignant');
    }

    public function superadmin()
    {
        return view('dashboards.superadmin');
    }
}
