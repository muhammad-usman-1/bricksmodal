<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCastingApplicationRequest;
use App\Http\Requests\StoreCastingApplicationRequest;
use App\Http\Requests\UpdateCastingApplicationRequest;
use App\Models\CastingApplication;
use App\Models\CastingRequirement;
use App\Models\TalentProfile;
use App\Support\EmailTemplateManager;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session as StripeCheckoutSession;
use Stripe\Stripe;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CastingApplicationController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('casting_application_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $castingApplications = CastingApplication::with(['casting_requirement', 'talent_profile'])->get();

        return view('admin.castingApplications.index', compact('castingApplications'));
    }

    public function create()
    {
        abort_if(Gate::denies('casting_application_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $casting_requirements = CastingRequirement::pluck('project_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $talent_profiles = TalentProfile::pluck('legal_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.castingApplications.create', compact('casting_requirements', 'talent_profiles'));
    }

    public function store(StoreCastingApplicationRequest $request)
    {
        $castingApplication = CastingApplication::create($request->all());

        return redirect()->route('admin.casting-applications.index');
    }

    public function edit(CastingApplication $castingApplication)
    {
        abort_if(Gate::denies('casting_application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $casting_requirements = CastingRequirement::pluck('project_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $talent_profiles = TalentProfile::pluck('legal_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $castingApplication->load('casting_requirement', 'talent_profile');

        return view('admin.castingApplications.edit', compact('castingApplication', 'casting_requirements', 'talent_profiles'));
    }

    public function update(UpdateCastingApplicationRequest $request, CastingApplication $castingApplication)
    {
        $castingApplication->update($request->all());

        return redirect()->route('admin.casting-applications.index');
    }

    public function show(CastingApplication $castingApplication)
    {
        abort_if(Gate::denies('casting_application_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $castingApplication->load('casting_requirement', 'talent_profile');

        return view('admin.castingApplications.show', compact('castingApplication'));
    }

    public function destroy(CastingApplication $castingApplication)
    {
        abort_if(Gate::denies('casting_application_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $castingApplication->delete();

        return back();
    }

    public function massDestroy(MassDestroyCastingApplicationRequest $request)
    {
        $castingApplications = CastingApplication::find(request('ids'));

        foreach ($castingApplications as $castingApplication) {
            $castingApplication->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function approve(Request $request, CastingApplication $castingApplication)
    {
        abort_if(Gate::denies('casting_application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $castingApplication->update([
            'status'      => 'selected',
            'admin_notes' => $data['admin_notes'] ?? null,
        ]);

        $talent = optional($castingApplication->talent_profile)->user;

        EmailTemplateManager::sendToUser($talent, 'talent_application_selected', [
            'project_name' => optional($castingApplication->casting_requirement)->project_name,
            'notes'        => $data['admin_notes'] ?? '',
        ], [
            'casting_application_id' => $castingApplication->id,
            'type'                   => 'application_selected',
            'fallback_subject'       => trans('notifications.application_selected_subject', ['project' => optional($castingApplication->casting_requirement)->project_name]),
            'fallback_body'          => trans('notifications.application_selected_body', ['project' => optional($castingApplication->casting_requirement)->project_name, 'notes' => $data['admin_notes'] ?? '']),
        ]);

        return back()->with('message', trans('notifications.application_approved'));
    }

    public function reject(Request $request, CastingApplication $castingApplication)
    {
        abort_if(Gate::denies('casting_application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'admin_notes' => ['required', 'string', 'max:1000'],
        ]);

        $castingApplication->update([
            'status'      => 'rejected',
            'admin_notes' => $data['admin_notes'],
        ]);

        $talent = optional($castingApplication->talent_profile)->user;

        EmailTemplateManager::sendToUser($talent, 'talent_application_rejected', [
            'project_name' => optional($castingApplication->casting_requirement)->project_name,
            'notes'        => $data['admin_notes'] ?? '',
        ], [
            'casting_application_id' => $castingApplication->id,
            'type'                   => 'application_rejected',
            'fallback_subject'       => trans('notifications.application_rejected_subject', ['project' => optional($castingApplication->casting_requirement)->project_name]),
            'fallback_body'          => trans('notifications.application_rejected_body', ['project' => optional($castingApplication->casting_requirement)->project_name, 'notes' => $data['admin_notes'] ?? '']),
        ]);

        return back()->with('message', trans('notifications.application_rejected'));
    }

    public function pay(Request $request, CastingApplication $castingApplication)
    {
        abort_if(Gate::denies('casting_application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($castingApplication->payment_processed === 'paid') {
            return back()->with('message', trans('notifications.application_already_paid'));
        }

        $amount = $castingApplication->rate_offered ?? $castingApplication->rate;

        if (! $amount || $amount <= 0) {
            return back()->withErrors(['payment' => trans('notifications.application_amount_missing')]);
        }

        $stripeSecret = config('services.stripe.secret');

        if (! $stripeSecret) {
            return back()->withErrors(['payment' => trans('notifications.stripe_missing_keys')]);
        }

        Stripe::setApiKey($stripeSecret);

        try {
            $successUrl = route('admin.payments.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}';
            $cancelUrl  = route('admin.payments.cancel', $castingApplication, true);

            $session = StripeCheckoutSession::create([
                'mode' => 'payment',
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $castingApplication->casting_requirement->project_name ?? 'Casting Project Payment',
                        ],
                        'unit_amount' => (int) ceil($amount * 100),
                    ],
                    'quantity' => 1,
                ]],
                'metadata' => [
                    'casting_application_id' => $castingApplication->id,
                ],
                'success_url' => $successUrl,
                'cancel_url'  => $cancelUrl,
            ]);

            $castingApplication->update([
                'stripe_session_id'    => $session->id,
                'payment_processed'    => 'pending',
            ]);

            return redirect($session->url);
        } catch (\Exception $exception) {
            Log::error('Stripe payment session error', [
                'application_id' => $castingApplication->id,
                'exception'      => $exception->getMessage(),
            ]);

            return back()->withErrors(['payment' => trans('notifications.payment_session_error', ['message' => $exception->getMessage()])]);
        }
    }

    public function paymentSuccess(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (! $sessionId) {
            return redirect()->route('admin.casting-requirements.index')->withErrors(['payment' => trans('notifications.payment_missing_session')]);
        }

        $stripeSecret = config('services.stripe.secret');

        if (! $stripeSecret) {
            return redirect()->route('admin.casting-requirements.index')->withErrors(['payment' => trans('notifications.stripe_missing_keys')]);
        }

        Stripe::setApiKey($stripeSecret);

        try {
            $session = StripeCheckoutSession::retrieve($sessionId);
        } catch (\Exception $exception) {
            Log::error('Stripe retrieve session error', ['session_id' => $sessionId, 'exception' => $exception->getMessage()]);
            return redirect()->route('admin.casting-requirements.index')->withErrors(['payment' => trans('notifications.payment_session_error')]);
        }

        $applicationId = $session->metadata['casting_application_id'] ?? null;
        $application   = $applicationId ? CastingApplication::find($applicationId) : null;

        if ($session->payment_status === 'paid' && $application) {
            $application->update([
                'payment_processed'     => 'paid',
                'stripe_session_id'     => $session->id,
                'stripe_payment_intent' => $session->payment_intent,
            ]);

            return redirect()->route('admin.casting-requirements.applicants', $application->casting_requirement_id)->with('message', trans('notifications.payment_success'));
        }

        return redirect()->route('admin.casting-requirements.index')->withErrors(['payment' => trans('notifications.payment_not_completed')]);
    }

    public function paymentCancel(CastingApplication $castingApplication)
    {
        return redirect()->route('admin.casting-requirements.applicants', $castingApplication->casting_requirement_id)->withErrors(['payment' => trans('notifications.payment_cancelled')]);
    }

    /**
     * Request payment approval from super admin
     */
    public function requestPayment(CastingApplication $castingApplication)
    {
        abort_if(Gate::denies('casting_application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Check if admin can make payments
        $admin = auth('admin')->user();
        if ($admin->canMakePayments()) {
            return back()->withErrors(['error' => 'You have permission to make payments directly.']);
        }

        // Check if payment is already requested or approved
        if (in_array($castingApplication->payment_status, ['requested', 'approved_by_super_admin', 'paid'])) {
            return back()->withErrors(['error' => 'Payment has already been requested or processed.']);
        }

        $castingApplication->update([
            'payment_status' => 'requested',
            'payment_requested_at' => now(),
            'payment_requested_by' => $admin->id,
        ]);

        return back()->with('message', 'Payment approval request sent to Super Admin.');
    }

    /**
     * Approve payment request (super admin only)
     */
    public function approvePayment(CastingApplication $castingApplication)
    {
        // Only super admin can approve payments
        $admin = auth('admin')->user();
        if (!$admin->isSuperAdmin()) {
            abort(403, 'Only Super Admin can approve payments.');
        }

        // Check if payment was requested
        if ($castingApplication->payment_status !== 'requested') {
            return back()->withErrors(['error' => 'This payment has not been requested for approval.']);
        }

        // Update payment status to approved
        $castingApplication->update([
            'payment_status' => 'approved_by_super_admin',
            'payment_approved_at' => now(),
            'payment_approved_by' => $admin->id,
        ]);

        // TODO: Send notification to the admin who requested payment
        // You can implement email notification here

        return back()->with('message', 'Payment approved successfully.');
    }
}
