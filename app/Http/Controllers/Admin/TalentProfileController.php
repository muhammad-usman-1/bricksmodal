<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTalentProfileRequest;
use App\Http\Requests\StoreTalentProfileRequest;
use App\Http\Requests\UpdateTalentProfileRequest;
use App\Models\Language;
use App\Models\TalentProfile;
use App\Models\User;
use App\Models\CastingApplication;
use App\Models\BankDetail;
use App\Support\EmailTemplateManager;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TalentProfileController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('talent_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $talentProfiles = TalentProfile::with(['languages', 'user'])->get();

        return view('admin.talentProfiles.index', compact('talentProfiles'));
    }

    public function create()
    {
        abort_if(Gate::denies('talent_profile_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $languages = Language::pluck('title', 'id');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.talentProfiles.create', compact('languages', 'users'));
    }

    public function store(StoreTalentProfileRequest $request)
    {
        $data = $request->all();
        $data['whatsapp_number'] = $this->sanitizePhoneNumber($data['whatsapp_number'] ?? null);

        $talentProfile = TalentProfile::create($data);
        $talentProfile->languages()->sync($request->input('languages', []));

        return redirect()->route('admin.talent-profiles.index');
    }

    public function edit(TalentProfile $talentProfile)
    {
        abort_if(Gate::denies('talent_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $languages = Language::pluck('title', 'id');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $talentProfile->load('languages', 'user');

        return view('admin.talentProfiles.edit', compact('languages', 'talentProfile', 'users'));
    }

    public function update(UpdateTalentProfileRequest $request, TalentProfile $talentProfile)
    {
        $data = $request->all();
        $data['whatsapp_number'] = $this->sanitizePhoneNumber($data['whatsapp_number'] ?? null);

        $talentProfile->update($data);
        $talentProfile->languages()->sync($request->input('languages', []));

        return redirect()->route('admin.talent-profiles.index');
    }

    public function show(TalentProfile $talentProfile)
    {
        abort_if(Gate::denies('talent_profile_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $talentProfile->load('languages', 'user', 'labels');

        return view('admin.talentProfiles.show', compact('talentProfile'));
    }

    public function destroy(TalentProfile $talentProfile)
    {
        abort_if(Gate::denies('talent_profile_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->removeTalentProfile($talentProfile, true);

        return back();
    }

    public function massDestroy(MassDestroyTalentProfileRequest $request)
    {
        $talentProfiles = TalentProfile::whereIn('id', (array) $request->input('ids'))->get();

        foreach ($talentProfiles as $talentProfile) {
            $this->removeTalentProfile($talentProfile, true);
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function approve(Request $request, TalentProfile $talentProfile)
    {
        abort_if(Gate::denies('talent_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $notes = $request->input('notes');

        $talentProfile->update([
            'verification_status' => 'approved',
            'verification_notes'  => $notes,
            'onboarding_step'     => 'completed',
            'onboarding_completed_at' => $talentProfile->onboarding_completed_at ?? now(),
        ]);

        $this->notifyTalent($talentProfile, 'approved', trans('notifications.talent_profile_approved'), $notes);

        return back()->with('message', trans('notifications.status_updated'));
    }

    public function reject(Request $request, TalentProfile $talentProfile)
    {
        abort_if(Gate::denies('talent_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'notes' => ['required', 'string', 'max:500'],
        ]);

        $talentProfile->update([
            'verification_status' => 'rejected',
            'verification_notes'  => $data['notes'],
            'onboarding_step'     => 'pending-approval',
        ]);

        $this->notifyTalent($talentProfile, 'rejected', trans('notifications.talent_profile_rejected'), $data['notes']);

        return back()->with('message', trans('notifications.status_updated'));
    }

    public function reactivate(Request $request, TalentProfile $talentProfile)
    {
        abort_if(Gate::denies('talent_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $notes = $request->input('notes');

        $talentProfile->update([
            'verification_status' => 'pending',
            'verification_notes'  => $notes,
            'onboarding_step'     => 'pending-approval',
        ]);

        $this->notifyTalent($talentProfile, 'pending', trans('notifications.talent_profile_reactivated'), $notes);

        return back()->with('message', trans('notifications.status_updated'));
    }

    protected function notifyTalent(TalentProfile $talentProfile, string $status, string $message, ?string $notes = null): void
    {
        $user = $talentProfile->user;

        if (! $user) {
            return;
        }

        EmailTemplateManager::sendToUser($user, 'talent_profile_' . $status, [
            'name'   => $user->name,
            'status' => ucfirst($status),
            'notes'  => $notes ?? '',
        ], [
            'talent_profile_id' => $talentProfile->id,
            'acted_by'          => optional(auth()->user())->name,
            'status'            => $status,
            'fallback_body'     => $message,
            'fallback_subject'  => trans('notifications.mail.subject'),
        ]);
    }

    protected function removeTalentProfile(TalentProfile $talentProfile, bool $notify = false): void
    {
        DB::transaction(function () use ($talentProfile, $notify) {
            $user = $talentProfile->user;

            if ($notify) {
                $this->notifyTalent($talentProfile, 'deleted', trans('notifications.talent_profile_deleted'));
            }

            $talentProfile->languages()->detach();
            $talentProfile->labels()->detach();
            CastingApplication::where('talent_profile_id', $talentProfile->id)->delete();
            BankDetail::where('talent_profile_id', $talentProfile->id)->delete();

            $talentProfile->delete();

            if ($user) {
                $user->roles()->detach();
                $user->forceDelete();
            }
        });
    }

    protected function sanitizePhoneNumber(?string $number): ?string
    {
        if (! $number) {
            return null;
        }

        $digits = preg_replace('/[^0-9]/', '', $number);

        return $digits ?: null;
    }
}
