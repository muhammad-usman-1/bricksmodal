<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ImpersonationController extends Controller
{
    public function start(Request $request, User $user)
    {
        abort_if(Gate::denies('impersonate_user'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $currentUser = $request->user('admin');

        if (! $currentUser) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        if ($user->id === $currentUser->id) {
            return redirect()->back()->with('message', trans('global.impersonation_same_user'));
        }

        if (session()->has('impersonate.original_admin_id')) {
            return redirect()->back()->with('message', trans('global.impersonation_active'));
        }

        session(['impersonate.original_admin_id' => $currentUser->id]);

        auth('admin')->login($user);

        return redirect()->route('admin.home')->with('message', trans('global.impersonation_started', ['name' => $user->name]));
    }

    public function stop(Request $request)
    {
        $originalId = session('impersonate.original_admin_id');

        if ($originalId) {
            auth('admin')->loginUsingId($originalId);
            session()->forget('impersonate.original_admin_id');

            return redirect()->route('admin.admin-management.index')->with('message', trans('global.impersonation_ended'));
        }

        return redirect()->route('admin.admin-management.index');
    }
}

