<div class="modal fade" id="approveApplicationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('notifications.approval_modal_default_title') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="#">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="approve_admin_notes">{{ trans('cruds.castingApplication.fields.admin_notes') }}</label>
                        <textarea name="admin_notes" id="approve_admin_notes" class="form-control" rows="4"></textarea>
                        <small class="form-text text-muted">{{ trans('notifications.approval_modal_note_help') }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('global.cancel') }}</button>
                    <button type="submit" class="btn btn-success">{{ trans('global.approve') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectApplicationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('notifications.rejection_modal_default_title') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="#">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="reject_admin_notes">{{ trans('cruds.castingApplication.fields.admin_notes') }}</label>
                        <textarea name="admin_notes" id="reject_admin_notes" class="form-control" rows="4" required></textarea>
                        <small class="form-text text-muted">{{ trans('notifications.rejection_modal_note_help') }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('global.cancel') }}</button>
                    <button type="submit" class="btn btn-danger">{{ trans('global.reject') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="applicationWhatsAppModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('notifications.send_whatsapp') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="application_whatsapp_message">{{ trans('notifications.whatsapp_message_label') }}</label>
                    <textarea id="application_whatsapp_message" class="form-control" rows="4"></textarea>
                    <small class="form-text text-muted">{{ trans('notifications.whatsapp_message_hint') }}</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('global.cancel') }}</button>
                <button type="button" class="btn btn-success" id="sendApplicationWhatsApp">{{ trans('notifications.send_whatsapp') }}</button>
            </div>
        </div>
    </div>
</div>

<style>
    .review-modal .modal-content { border-radius: 14px; border: 1px solid #e5e7eb; box-shadow: 0 16px 48px rgba(0,0,0,0.15); }
    .review-modal .modal-header { background: #0f1524; color: #fff; border-bottom: 1px solid #0f1524; border-top-left-radius: 14px; border-top-right-radius: 14px; }
    .review-modal .modal-title { font-weight: 600; font-size: 16px; }
    .review-modal .close { color: #fff; opacity: 1; }
    .review-modal .modal-body { padding: 18px 20px; }
    .review-modal label.required::after { content:' *'; color: #e11d48; }
    .review-modal .form-control { border-radius: 10px; border: 1px solid #d1d5db; box-shadow: inset 0 1px 2px rgba(0,0,0,0.04); }
    .review-modal .form-control:focus { border-color: #0f1524; box-shadow: 0 0 0 3px rgba(15,21,36,0.12); }
    .review-modal .star-rating { display: inline-flex; gap: 10px; align-items: center; }
    .review-modal .star-rating input { display: none; }
    .review-modal .star-rating label { cursor: pointer; color: #d4d4d8; font-size: 24px; transition: none; }
    .review-modal .star-rating label.active { color: #f5a524; }
    .review-modal .modal-footer { border-top: 1px solid #e5e7eb; padding: 14px 20px; }
    .review-modal .btn-cancel { background: #f3f4f6; color: #111827; border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px 16px; }
    .review-modal .btn-primary { background: #0f1524; border-color: #0f1524; border-radius: 10px; padding: 10px 18px; font-weight: 600; }
    .review-modal .btn-primary:hover { background: #111827; border-color: #111827; }
    .review-modal small.form-text { color: #6b7280; }
</style>

<div class="modal fade review-modal" id="requestPaymentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add Review') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="#">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="required d-block mb-2">{{ __('Rating') }}</label>
                        <div class="star-rating" data-star-rating>
                            @for($i = 1; $i <= 5; $i++)
                                <input type="radio" id="request_rating_{{ $i }}" name="rating" value="{{ $i }}">
                                <label for="request_rating_{{ $i }}" data-value="{{ $i }}"><i class="fas fa-star"></i></label>
                            @endfor
                        </div>
                        <small class="form-text text-muted">{{ __('How satisfied was the client with this talent?') }}</small>
                    </div>
                    <div class="form-group mb-0">
                        <label for="request_reviews" class="required">{{ __('Review') }}</label>
                        <textarea name="reviews" id="request_reviews" class="form-control" rows="4" required placeholder="{{ __('Share a quick review that the Super Admin can read before approving payment.') }}"></textarea>
                        <small class="form-text text-muted">{{ __('Minimum 10 characters. This will be visible to the Super Admin.') }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">{{ trans('global.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Send Payment Request') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
