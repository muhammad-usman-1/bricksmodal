<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('talent.dashboard.index');
    }
}
