# Email Control System

## Overview
This project now includes a simple on/off switch for all email notifications controlled via the `.env` file. The email functionality remains intact and can be easily enabled or disabled without code changes.

## Configuration

### Enable/Disable Email Sending

In your `.env` file, set:

```env
# Disable all email sending
MAIL_ENABLED=false

# Enable all email sending
MAIL_ENABLED=true
```

**Default**: `true` (emails are sent by default if not specified)

### Current Setting
```env
MAIL_ENABLED=false
```
✅ **Email sending is currently DISABLED**

## How It Works

### 1. Environment Variable
- Added `MAIL_ENABLED` to `.env` file
- Controls whether emails are sent or not

### 2. Configuration File
- Added `enabled` setting to `config/mail.php`
- Reads from `MAIL_ENABLED` environment variable

### 3. Custom Mail Channel
- Created `App\Channels\MailChannel` that extends Laravel's default mail channel
- Checks `config('mail.enabled')` before sending
- Logs skipped emails when disabled

### 4. Service Provider
- Created `App\Providers\NotificationServiceProvider`
- Overrides Laravel's mail channel with our custom one
- Registered in `config/app.php`

## Features

✅ **No Code Changes Required**: Just update `.env` file  
✅ **All Emails Controlled**: Works for all notifications  
✅ **Logging**: Skipped emails are logged for debugging  
✅ **Easy to Enable**: Set `MAIL_ENABLED=true` anytime  
✅ **Development Friendly**: Disable emails during testing  

## Email Types Affected

When `MAIL_ENABLED=false`, the following notifications will NOT be sent:

### Admin Notifications
- `AdminAccountCreated` - New admin account creation
- `CastingApplicationSubmitted` - Talent applies to casting
- `TalentProfileSubmitted` - New talent registration

### Payment Notifications
- `PaymentRequested` - Talent requests payment
- `PaymentApproved` - Super admin approves payment
- `PaymentRejected` - Super admin rejects payment
- `PaymentReleased` - Payment released via Stripe

### Template Emails
- `TemplateEmailNotification` - Custom template emails

### System Emails
- Password reset emails
- Email verification emails
- Any other Laravel notifications using the mail channel

## Log Messages

When emails are disabled, you'll see log entries like:

```
[timestamp] local.INFO: Email sending is disabled. Skipping notification. 
{
    "notification": "App\\Notifications\\PaymentReleased",
    "notifiable": "App\\Models\\User",
    "notifiable_id": 123
}
```

## Usage Examples

### For Development
```env
# Disable emails during local development
MAIL_ENABLED=false
```

### For Testing
```env
# Test without sending real emails
MAIL_ENABLED=false
```

### For Production
```env
# Enable emails in production
MAIL_ENABLED=true
```

## Testing

### Test with Emails Disabled
1. Set `MAIL_ENABLED=false` in `.env`
2. Clear config cache: `php artisan config:clear`
3. Trigger any notification (e.g., submit application)
4. Check `storage/logs/laravel.log` for skip messages
5. Verify no emails were sent

### Test with Emails Enabled
1. Set `MAIL_ENABLED=true` in `.env`
2. Clear config cache: `php artisan config:clear`
3. Trigger any notification
4. Check email inbox (or Mailtrap)
5. Verify emails were sent

## Troubleshooting

### Emails still sending when disabled?
```bash
# Clear the config cache
php artisan config:clear

# Restart your server if using php artisan serve
```

### Want to check current setting?
```bash
php artisan tinker
>>> config('mail.enabled')
=> false
```

### Emails not sending when enabled?
1. Check `.env`: `MAIL_ENABLED=true`
2. Verify SMTP settings are correct
3. Check `storage/logs/laravel.log` for errors
4. Test SMTP connection

## Reverting to Normal Email Sending

To completely remove this feature and go back to default Laravel behavior:

1. Remove `MAIL_ENABLED` from `.env`
2. Remove `'enabled'` from `config/mail.php`
3. Delete `app/Channels/MailChannel.php`
4. Delete `app/Providers/NotificationServiceProvider.php`
5. Remove provider from `config/app.php`
6. Run `php artisan config:clear`

## Files Modified/Created

### Created:
1. `app/Channels/MailChannel.php` - Custom mail channel
2. `app/Providers/NotificationServiceProvider.php` - Service provider

### Modified:
1. `.env` - Added `MAIL_ENABLED=false`
2. `config/mail.php` - Added `'enabled'` configuration
3. `config/app.php` - Registered NotificationServiceProvider

## Benefits

### For Development
- No spam emails during testing
- Faster development (no SMTP delays)
- No need for fake SMTP services

### For Production
- Easy emergency email disable
- Can disable temporarily during maintenance
- Simple on/off switch for troubleshooting

### For Testing
- Test notification logic without sending emails
- Review what would be sent via logs
- No test emails cluttering inboxes

## Advanced: Selective Email Disabling

If you want to disable only specific notification types, you can modify individual notification classes:

```php
public function via($notifiable)
{
    if (!config('mail.enabled')) {
        return []; // Don't send via any channel
    }
    
    return ['mail', 'database']; // Normal channels
}
```

## Notes

- The email functionality code remains completely intact
- All notification classes work normally
- Only the actual sending is controlled
- Can be toggled anytime without deployment
- Logs provide visibility into skipped emails

---

**Status**: ✅ Email sending is currently **DISABLED**  
**To Enable**: Set `MAIL_ENABLED=true` in `.env` and run `php artisan config:clear`  
**Last Updated**: November 7, 2025
