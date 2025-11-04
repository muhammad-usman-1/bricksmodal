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
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session as StripeCheckoutSession;
use Stripe\Stripe;
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

    public function updateStatus(Request $request, CastingApplication $castingApplication)
    {
        abort_if(Gate::denies('casting_application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'status' => ['required', 'string', 'in:selected,rejected,applied'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $castingApplication->update([
            'status' => $data['status'],
            'admin_notes' => $data['admin_notes'] ?? null,
        ]);

        // Send email and WhatsApp notifications
        $talent = optional($castingApplication->talent_profile)->user;
        $talentProfile = $castingApplication->talent_profile;
        $projectName = optional($castingApplication->casting_requirement)->project_name;

        if ($data['status'] === 'selected') {
            // Send approval email
            EmailTemplateManager::sendToUser($talent, 'talent_application_selected', [
                'project_name' => $projectName,
                'notes'        => $data['admin_notes'] ?? '',
            ], [
                'casting_application_id' => $castingApplication->id,
                'type'                   => 'application_selected',
                'fallback_subject'       => trans('notifications.application_selected_subject', ['project' => $projectName]),
                'fallback_body'          => trans('notifications.application_selected_body', ['project' => $projectName, 'notes' => $data['admin_notes'] ?? '']),
            ]);

            // Send WhatsApp notification
            $this->sendWhatsAppNotification($talentProfile, 'selected', $projectName, $data['admin_notes'] ?? '');

        } elseif ($data['status'] === 'rejected') {
            // Send rejection email
            EmailTemplateManager::sendToUser($talent, 'talent_application_rejected', [
                'project_name' => $projectName,
                'notes'        => $data['admin_notes'] ?? '',
            ], [
                'casting_application_id' => $castingApplication->id,
                'type'                   => 'application_rejected',
                'fallback_subject'       => trans('notifications.application_rejected_subject', ['project' => $projectName]),
                'fallback_body'          => trans('notifications.application_rejected_body', ['project' => $projectName, 'notes' => $data['admin_notes'] ?? '']),
            ]);

            // Send WhatsApp notification
            $this->sendWhatsAppNotification($talentProfile, 'rejected', $projectName, $data['admin_notes'] ?? '');
        }

        $statusText = [
            'selected' => 'approved',
            'rejected' => 'rejected',
            'applied' => 'marked as applied',
        ][$data['status']] ?? $data['status'];

        return back()->with('message', "Application has been {$statusText} successfully. Notifications sent to talent.");
    }

    public function approve(Request $request, CastingApplication $castingApplication)
    {
        abort_if(Gate::denies('casting_application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'casting_requirement_id' => ['required', 'integer', 'exists:casting_requirements,id'],
            'talent_profile_id' => ['required', 'integer', 'exists:talent_profiles,id'],
            'rate' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'in:selected,rejected,pending'],
            'payment_processed' => ['required', 'string', 'in:pending,paid,not_paid'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $castingApplication->update([
            'casting_requirement_id' => $data['casting_requirement_id'],
            'talent_profile_id' => $data['talent_profile_id'],
            'rate' => $data['rate'],
            'status' => 'selected',
            'payment_processed' => $data['payment_processed'],
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
     * Send WhatsApp notification to talent
     */
    private function sendWhatsAppNotification($talentProfile, $status, $projectName, $notes = '')
    {
        if (!$talentProfile || !$talentProfile->whatsapp_number) {
            return false;
        }

        try {
            $smsService = new \App\Services\KwtSmsService();

            // Prepare the message based on status
            if ($status === 'selected') {
                $message = "🎉 Congratulations! You have been SELECTED for the project: {$projectName}";
                if ($notes) {
                    $message .= "\n\nAdditional Notes: {$notes}";
                }
                $message .= "\n\nPlease check your email for further details.";
            } else {
                $message = "Thank you for applying to: {$projectName}. Unfortunately, you were not selected for this project.";
                if ($notes) {
                    $message .= "\n\nFeedback: {$notes}";
                }
                $message .= "\n\nDon't worry, keep applying to other projects!";
            }

            // Extract country code and number from WhatsApp number
            $whatsappNumber = preg_replace('/[^0-9+]/', '', $talentProfile->whatsapp_number);

            // If number starts with +, extract country code
            if (str_starts_with($whatsappNumber, '+')) {
                // For Kuwait numbers (+965), extract country code
                if (str_starts_with($whatsappNumber, '+965')) {
                    $countryCode = '965';
                    $number = substr($whatsappNumber, 4);
                } else {
                    // Generic extraction for other countries
                    $countryCode = substr($whatsappNumber, 1, 3); // Assume 3-digit country code
                    $number = substr($whatsappNumber, 4);
                }
            } else {
                // Assume Kuwait if no country code
                $countryCode = '965';
                $number = ltrim($whatsappNumber, '0'); // Remove leading zero if present
            }

            // Send SMS using the existing OTP method (repurposing for notifications)
            return $this->sendCustomSms($countryCode, $number, $message);

        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed', [
                'talent_profile_id' => $talentProfile->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send custom SMS message
     */
    private function sendCustomSms(string $countryCode, string $phoneNumber, string $message): bool
    {
        try {
            $smsService = new \App\Services\KwtSmsService();

            // Use reflection to access the private sendSms method or create a custom implementation
            $apiUrl = 'https://www.kwtsms.com/API/send/';
            $username = env('KWT_SMS_USERNAME', 'brickskw');
            $password = env('KWT_SMS_PASSWORD', 'sNdBfF@g988');
            $sender = env('KWT_SMS_SENDER', 'KWT-SMS');

            // Format phone number
            $country = preg_replace('/[^0-9]/', '', (string)$countryCode);
            $number = preg_replace('/[^0-9]/', '', (string)$phoneNumber);

            if (strlen($number) > 1 && $number[0] === '0') {
                $number = ltrim($number, '0');
            }

            $mobile = $country . $number;

            // Build request params
            $params = [
                'username' => $username,
                'password' => $password,
                'sender'   => $sender,
                'mobile'   => $mobile,
                'lang'     => '1', // 1 for English
                'message'  => $message,
            ];

            $response = \Illuminate\Support\Facades\Http::timeout(10)->get($apiUrl, $params);
            $status = $response->status();
            $body = trim($response->body());

            Log::info('WhatsApp notification sent', [
                'mobile' => $mobile,
                'status' => $status,
                'response' => $body
            ]);

            return $response->successful() && stripos($body, 'ERR') === false;

        } catch (\Exception $e) {
            Log::error('Custom SMS failed', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
