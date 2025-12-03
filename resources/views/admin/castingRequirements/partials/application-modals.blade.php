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

<div class="modal fade" id="requestPaymentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Request Payment') }}</h5>
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
                            @for($i = 5; $i >= 1; $i--)
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('global.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Send Payment Request') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
