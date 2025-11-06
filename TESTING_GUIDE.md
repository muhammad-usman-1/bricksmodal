# Testing Guide - KWT SMS OTP Integration

## Prerequisites
- Ensure your `.env` file has the KWT SMS credentials:
  ```
  KWT_SMS_USERNAME=brickskw
  KWT_SMS_PASSWORD=sNdBfF@g988
  KWT_SMS_SENDER=KWT-SMS
  ```

## Test Scenarios

### 1. Login with Valid Phone Number (Supported Country)

**Steps:**
1. Navigate to `/talent/login`
2. Click on the country dropdown
3. Notice that only supported countries are available (Kuwait, Saudi Arabia, UAE, Bahrain, Oman, Qatar, Egypt, Jordan, Lebanon, Syria, Iraq, Yemen, Palestine)
4. Select Kuwait (+965)
5. Enter a valid mobile number (e.g., 51557699)
6. Click "Submit"
7. You should be redirected to the OTP verification page
8. Check the mobile phone for the SMS with OTP code
9. Enter the 6-digit OTP code
10. Click "Verify"
11. You should be logged in successfully

**Expected Result:**
- SMS is sent to the mobile number
- OTP is valid for 5 minutes
- Successful verification logs the user in

### 2. Login with Invalid OTP

**Steps:**
1. Complete steps 1-7 from Test Scenario 1
2. Enter an incorrect OTP (e.g., 000000)
3. Click "Verify"

**Expected Result:**
- Error message: "Invalid OTP."
- User stays on OTP verification page
- Can try again with correct OTP

### 3. Login with Expired OTP

**Steps:**
1. Complete steps 1-7 from Test Scenario 1
2. Wait for 6+ minutes
3. Enter the OTP code
4. Click "Verify"

**Expected Result:**
- Error message: "OTP expired. Please request a new one."
- Redirected back to login page

### 4. SMS Sending Failure Handling

**Steps:**
1. Temporarily change credentials in `.env` to invalid values
2. Try to login with a phone number
3. Click "Submit"

**Expected Result:**
- Error message: "Failed to send OTP. Please try again."
- OTP is logged in application logs for testing purposes
- User can retry

### 5. Country Restriction Verification

**Steps:**
1. Navigate to `/talent/login`
2. Click on the country dropdown
3. Try to search for unsupported countries (e.g., United States, United Kingdom, Pakistan, India)

**Expected Result:**
- Only the 13 supported Middle Eastern countries appear in the list
- Unsupported countries are not visible

### 6. Register New User (Verify Country Restrictions)

**Steps:**
1. Navigate to `/talent/register`
2. Click "Create account" tab
3. Check the country dropdown

**Expected Result:**
- Same country restrictions apply as login page
- Only supported countries are visible

## Monitoring & Debugging

### Check Application Logs
```bash
tail -f storage/logs/laravel.log
```

Look for these log entries:
- `KWT SMS API Response` - Shows API response details
- `OTP sent successfully via SMS` - Confirms SMS was sent
- `Failed to send OTP SMS` - Indicates SMS failure
- `Talent OTP (SMS failed) for phone` - Shows OTP when SMS fails (for testing)

### Log Format Examples

**Success:**
```
[timestamp] local.INFO: OTP sent successfully via SMS {"user_id":123,"mobile":"96551557699"}
[timestamp] local.INFO: KWT SMS API Response {"mobile":"96551557699","status":200,"response":"Success"}
```

**Failure:**
```
[timestamp] local.ERROR: Failed to send OTP SMS {"user_id":123,"mobile":"96551557699","error":"Failed to send OTP"}
[timestamp] local.INFO: Talent OTP (SMS failed) for phone +96551557699: 123456
```

## Database Verification

### Check OTP Storage
```sql
SELECT id, phone_country_code, phone_number, otp, otp_expires_at, otp_consumed, otp_attempts 
FROM users 
WHERE type = 'talent' 
ORDER BY created_at DESC 
LIMIT 10;
```

**Fields to verify:**
- `otp`: Should be NULL after successful verification
- `otp_expires_at`: Should be 5 minutes after generation
- `otp_consumed`: Should be 1 (true) after successful verification
- `otp_attempts`: Increments with each failed OTP attempt

## Troubleshooting

### Issue: SMS not received
**Possible Causes:**
1. Invalid KWT SMS credentials
2. Mobile number format incorrect
3. Country code not supported by KWT SMS
4. Network issues

**Solution:**
- Check application logs for API response
- Verify mobile number format in database
- Ensure KWT SMS account has sufficient credits
- Test with different mobile numbers

### Issue: "Failed to send OTP" error
**Possible Causes:**
1. Wrong credentials in `.env`
2. KWT SMS API is down
3. HTTP client timeout

**Solution:**
- Verify credentials in `.env` match KWT SMS account
- Check KWT SMS service status
- Review application logs for detailed error messages

### Issue: Unsupported country appearing in dropdown
**Possible Causes:**
1. Configuration not loaded
2. Cache issue

**Solution:**
```bash
php artisan config:clear
php artisan cache:clear
```

## API Testing (Manual)

You can test the KWT SMS API directly using curl:

```bash
curl "https://www.kwtsms.com/API/send/?username=brickskw&password=sNdBfF@g988&sender=KWT-SMS&mobile=96551557699&lang=1&message=Test%20message"
```

Replace `96551557699` with your actual test number.

## Performance Notes

- OTP generation: Instant
- SMS delivery: Usually 2-10 seconds
- OTP expiration: 5 minutes
- Session timeout: Configurable in `config/session.php`

## Security Checklist

✅ OTP expires after 5 minutes  
✅ OTP can only be used once  
✅ Failed attempts are tracked  
✅ Mobile numbers are sanitized  
✅ All API calls are logged  
✅ Credentials stored in environment variables  
✅ Only supported countries allowed  

## Next Steps

1. Test with real mobile numbers in supported countries
2. Monitor logs for any errors or issues
3. Adjust OTP message template if needed (in `KwtSmsService.php`)
4. Configure rate limiting for OTP requests (optional)
5. Add resend OTP functionality (optional)
