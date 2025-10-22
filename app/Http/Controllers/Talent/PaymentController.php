<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Models\CastingApplication;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $profile = $request->user('talent')->talentProfile;

        $applications = CastingApplication::with('casting_requirement')
            ->where('talent_profile_id', $profile->id)
            ->where('status', 'selected')
            ->latest()
            ->get();

        return view('talent.payments.index', compact('applications'));
    }
}
