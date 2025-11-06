<div class="modal fade" id="talentActionModal" tabindex="-1" role="dialog" aria-labelledby="talentActionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="talentActionModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="#">
                @csrf
                <div class="modal-body">
                    <div class="form-group notes-group">
                        <label for="notes">{{ trans('cruds.talentProfile.fields.verification_notes') }}</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group whatsapp-group">
                        <label for="whatsapp_message">{{ trans('notifications.whatsapp_message_label') }}</label>
                        <textarea id="whatsapp_message" class="form-control" rows="3"></textarea>
                        <small class="form-text text-muted">{{ trans('notifications.whatsapp_message_hint') }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success mr-auto" id="sendWhatsAppAction">{{ trans('notifications.send_whatsapp') }}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('global.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ trans('global.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
