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

        $user = auth('admin')->user();
        $isSuperAdmin = $user->isSuperAdmin();

        // Payment status statistics
        $applicationsStats = [
            'total'    => CastingApplication::where('status', 'selected')->count(),
            'pending'  => CastingApplication::where('payment_status', 'pending')->count(),
            'requested' => CastingApplication::where('payment_status', 'requested')->count(),
            'approved'  => CastingApplication::where('payment_status', 'approved')->count(),
            'released' => CastingApplication::where('payment_status', 'released')->count(),
            'received' => CastingApplication::where('payment_status', 'received')->count(),
        ];

        // Financial statistics
        $financials = [
            'total_requested' => CastingApplication::where('status', 'selected')
                ->get()
                ->sum(function($app) {
                    return $app->getPaymentAmount();
                }),
            'total_pending' => CastingApplication::whereIn('payment_status', ['pending', 'requested', 'approved'])
                ->get()
                ->sum(function($app) {
                    return $app->getPaymentAmount();
                }),
            'total_released' => CastingApplication::whereIn('payment_status', ['released', 'received'])
                ->get()
                ->sum(function($app) {
                    return $app->getPaymentAmount();
                }),
            'total_offered' => CastingApplication::where('status', 'selected')
                ->whereNotNull('rate_offered')
                ->sum('rate_offered'),
        ];

        // Recent payments - show different data based on user role
        if ($isSuperAdmin) {
            // Super admin sees all payment requests
            $recentPayments = CastingApplication::with([
                'casting_requirement',
                'talent_profile.user',
                'requestedByAdmin'
            ])
            ->where('status', 'selected')
            ->whereIn('payment_status', ['requested', 'approved', 'released', 'received'])
            ->latest('payment_requested_at')
            ->take(10)
            ->get();
        } else {
            // Regular admin sees only their payment requests
            $recentPayments = CastingApplication::with([
                'casting_requirement',
                'talent_profile.user'
            ])
            ->where('status', 'selected')
            ->where('payment_requested_by_admin_id', $user->id)
            ->latest('payment_requested_at')
            ->take(10)
            ->get();
        }

        $recentBankAccounts = BankDetail::with('talent_profile')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboards.payments', compact(
            'applicationsStats',
            'financials',
            'recentPayments',
            'recentBankAccounts',
            'isSuperAdmin'
        ));
    }
}
