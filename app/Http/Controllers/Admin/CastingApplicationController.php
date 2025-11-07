<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCastingApplicationRequest;
use App\Http\Requests\StoreCastingApplicationRequest;
use App\Http\Requests\UpdateCastingApplicationRequest;
use App\Models\CastingApplication;
use App\Models\CastingRequirement;
use App\Models\TalentProfile;
use App\Models\User;
use App\Notifications\PaymentRequested;
use App\Support\EmailTemplateManager;
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

    /**
     * Approve a casting application
     * Changes status to 'selected' and initializes payment workflow
     */
    public function approve(Request $request, CastingApplication $castingApplication)
    {
        abort_if(Gate::denies('casting_application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Validate that application is in 'applied' status
        if ($castingApplication->status !== 'applied') {
            return back()->withErrors(['error' => 'Only applications with "applied" status can be approved.']);
        }

        $data = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1000'],
            'rate_offered' => ['nullable', 'numeric', 'min:0'],
        ]);

        // Update application status and initialize payment workflow
        $castingApplication->update([
            'status'         => 'selected',
            'admin_notes'    => $data['admin_notes'] ?? null,
            'rate_offered'   => $data['rate_offered'] ?? null,
            'payment_status' => 'pending', // Initialize payment status
        ]);

        // Send notification to talent
        $talent = optional($castingApplication->talent_profile)->user;
        if ($talent) {
            EmailTemplateManager::sendToUser($talent, 'talent_application_selected', [
                'project_name' => optional($castingApplication->casting_requirement)->project_name,
                'notes'        => $data['admin_notes'] ?? '',
            ], [
                'casting_application_id' => $castingApplication->id,
                'type'                   => 'application_selected',
                'fallback_subject'       => trans('notifications.application_selected_subject', ['project' => optional($castingApplication->casting_requirement)->project_name]),
                'fallback_body'          => trans('notifications.application_selected_body', ['project' => optional($castingApplication->casting_requirement)->project_name, 'notes' => $data['admin_notes'] ?? '']),
            ]);
        }

        return back()->with('message', 'Application approved successfully. Talent can now request payment.');
    }

    /**
     * Reject a casting application
     */
    public function reject(Request $request, CastingApplication $castingApplication)
    {
        abort_if(Gate::denies('casting_application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Validate that application is not already rejected
        if ($castingApplication->status === 'rejected') {
            return back()->withErrors(['error' => 'Application is already rejected.']);
        }

        $data = $request->validate([
            'admin_notes' => ['required', 'string', 'max:1000'],
        ]);

        $castingApplication->update([
            'status'      => 'rejected',
            'admin_notes' => $data['admin_notes'],
        ]);

        // Send notification to talent
        $talent = optional($castingApplication->talent_profile)->user;
        if ($talent) {
            EmailTemplateManager::sendToUser($talent, 'talent_application_rejected', [
                'project_name' => optional($castingApplication->casting_requirement)->project_name,
                'notes'        => $data['admin_notes'] ?? '',
            ], [
                'casting_application_id' => $castingApplication->id,
                'type'                   => 'application_rejected',
                'fallback_subject'       => trans('notifications.application_rejected_subject', ['project' => optional($castingApplication->casting_requirement)->project_name]),
                'fallback_body'          => trans('notifications.application_rejected_body', ['project' => optional($castingApplication->casting_requirement)->project_name, 'notes' => $data['admin_notes'] ?? '']),
            ]);
        }

        return back()->with('message', 'Application rejected successfully.');
    }

    /**
     * Request payment approval from super admin (for regular admins)
     */
    public function requestPayment(CastingApplication $castingApplication)
    {
        abort_if(Gate::denies('casting_application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $admin = auth('admin')->user();

        // Super admins don't need to request approval
        if ($admin->isSuperAdmin()) {
            return back()->withErrors(['error' => 'Super admins can directly release payments.']);
        }

        // Check if application is selected
        if ($castingApplication->status !== 'selected') {
            return back()->withErrors(['error' => 'Can only request payment for selected applications.']);
        }

        // Check if payment is already in process
        if (in_array($castingApplication->payment_status, ['requested', 'approved', 'released', 'received'])) {
            return back()->withErrors(['error' => 'Payment request already in progress or completed.']);
        }

        $castingApplication->update([
            'payment_status' => 'requested',
            'payment_requested_at' => now(),
            'payment_requested_by_admin_id' => $admin->id,
        ]);

        // Notify all super admins about payment request
        $superAdmins = User::where('is_super_admin', true)->where('type', 'admin')->get();
        foreach ($superAdmins as $superAdmin) {
            $superAdmin->notify(new PaymentRequested($castingApplication));
        }

        return back()->with('message', 'Payment request sent to Super Admin for approval.');
    }

    /**
     * Approve payment request (super admin only)
     */
    public function approvePayment(CastingApplication $castingApplication)
    {
        $admin = auth('admin')->user();

        if (!$admin->isSuperAdmin()) {
            abort(403, 'Only Super Admin can approve payments.');
        }

        if ($castingApplication->payment_status !== 'requested') {
            return back()->withErrors(['error' => 'This payment has not been requested for approval.']);
        }

        $castingApplication->update([
            'payment_status' => 'approved',
            'payment_approved_at' => now(),
            'payment_approved_by_super_admin_id' => $admin->id,
        ]);

        // TODO: Notify requesting admin about approval

        return back()->with('message', 'Payment request approved. You can now release the payment.');
    }

    /**
     * Reject payment request (super admin only)
     */
    public function rejectPayment(Request $request, CastingApplication $castingApplication)
    {
        $admin = auth('admin')->user();

        if (!$admin->isSuperAdmin()) {
            abort(403, 'Only Super Admin can reject payment requests.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        if ($castingApplication->payment_status !== 'requested') {
            return back()->withErrors(['error' => 'This payment has not been requested for approval.']);
        }

        $castingApplication->update([
            'payment_status' => 'rejected',
            'payment_rejection_reason' => $validated['rejection_reason'],
        ]);

        // TODO: Notify requesting admin about rejection

        return back()->with('message', 'Payment request rejected.');
    }

    /**
     * Release payment to talent (super admin only)
     */
    public function releasePayment(CastingApplication $castingApplication)
    {
        $admin = auth('admin')->user();

        if (!$admin->isSuperAdmin()) {
            abort(403, 'Only Super Admin can release payments.');
        }

        if ($castingApplication->payment_status !== 'approved') {
            return back()->withErrors(['error' => 'Payment must be approved before releasing.']);
        }

        // Check if talent has card details
        $talentProfile = $castingApplication->talent_profile;
        if (!$talentProfile || !$talentProfile->hasCardDetails()) {
            return back()->withErrors(['error' => 'Talent has not provided card details yet.']);
        }

        // TODO: Integrate with payment gateway to send money to talent's card
        // For now, we'll just mark as released

        $castingApplication->update([
            'payment_status' => 'released',
            'payment_released_at' => now(),
        ]);

        // TODO: Notify talent about payment release

        return back()->with('message', 'Payment released to talent successfully.');
    }
}
