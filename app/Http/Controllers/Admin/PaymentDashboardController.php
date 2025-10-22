<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankDetail;
use App\Models\CastingApplication;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class PaymentDashboardController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationsStats = [
            'total'    => CastingApplication::count(),
            'paid'     => CastingApplication::where('payment_processed', 'paid')->count(),
            'pending'  => CastingApplication::where('payment_processed', 'pending')->count(),
            'applied'  => CastingApplication::where('status', 'applied')->count(),
            'selected' => CastingApplication::where('status', 'selected')->count(),
        ];

        $financials = [
            'total_requested' => CastingApplication::sum('rate'),
            'total_offered'   => CastingApplication::sum('rate_offered'),
        ];

        $recentPayments = CastingApplication::with(['casting_requirement', 'talent_profile'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        $recentBankAccounts = BankDetail::with('talent_profile')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboards.payments', compact(
            'applicationsStats',
            'financials',
            'recentPayments',
            'recentBankAccounts'
        ));
    }
}
