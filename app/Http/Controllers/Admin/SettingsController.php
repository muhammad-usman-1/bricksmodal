<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('system_settings_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $settings = AdminSetting::singleton();

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Get the maximum possible file upload size based on PHP configuration
     * Returns size in kilobytes
     */
    private function getMaxFileSize()
    {
        $uploadMax = $this->parseSize(ini_get('upload_max_filesize'));
        $postMax = $this->parseSize(ini_get('post_max_size'));

        // The actual max is the smaller of the two
        $maxSize = min($uploadMax, $postMax);

        // Minimum requirement: 40MB (40960 KB)
        // If PHP limits are less than 40MB, use 40MB as minimum
        // Otherwise, use 99% of the PHP limit to leave a minimal buffer
        $minimumSize = 40960; // 40MB in KB

        if ($maxSize <= 0 || $maxSize < $minimumSize) {
            // Use at least 40MB, or the PHP limit if it's higher
            $maxSize = max($minimumSize, $maxSize);
        } else {
            // Use 99% of the limit to leave a minimal buffer for other form data
            // This maximizes file size while still accounting for form overhead
            $maxSize = (int) ($maxSize * 0.99);
            // Ensure it's at least 40MB
            $maxSize = max($minimumSize, $maxSize);
        }

        return (int) $maxSize;
    }

    /**
     * Parse PHP size string (e.g., "10M", "512K") to kilobytes
     */
    private function parseSize($size)
    {
        $size = trim($size);
        if (empty($size) || $size === '0') {
            return 0;
        }

        // Handle numeric-only values (assumed to be in bytes)
        if (is_numeric($size)) {
            return (int) ($size / 1024);
        }

        $last = strtolower($size[strlen($size) - 1]);
        $value = (float) $size;

        switch ($last) {
            case 'g':
                $value *= 1024 * 1024; // Convert GB to KB
                break;
            case 'm':
                $value *= 1024; // Convert MB to KB
                break;
            case 'k':
                $value *= 1; // Already in KB
                break;
            default:
                // Assume bytes, convert to KB
                $value = $value / 1024;
        }

        return (int) $value;
    }

    public function update(Request $request)
    {
        abort_if(Gate::denies('system_settings_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Check if POST data was truncated due to post_max_size limit
        // When post_max_size is exceeded, $_POST and $_FILES will be empty but Content-Length header will show the actual size
        $contentLength = $request->header('Content-Length');
        if ($request->method() === 'POST' && $contentLength) {
            $contentLengthBytes = (int) $contentLength;
            $postMaxSize = $this->parseSize(ini_get('post_max_size'));
            $postMaxBytes = $postMaxSize * 1024;

            // If Content-Length exceeds post_max_size and request is empty, PHP rejected it
            if ($contentLengthBytes > $postMaxBytes && empty($request->all()) && empty($request->allFiles())) {
                $contentLengthMB = round($contentLengthBytes / (1024 * 1024), 2);
                $postMaxMB = round($postMaxBytes / (1024 * 1024), 2);
                $uploadMaxMB = round($this->parseSize(ini_get('upload_max_filesize')) / 1024, 2);
                return back()->with('error', "The file size ({$contentLengthMB} MB) exceeds PHP's post_max_size limit of {$postMaxMB} MB. Current PHP limits: post_max_size = {$postMaxMB} MB, upload_max_filesize = {$uploadMaxMB} MB. Please contact your server administrator to increase these values in php.ini to at least 50MB.");
            }
        }

        try {
            $validated = $request->validate([
                'email_notifications' => ['sometimes', 'boolean'],
                'push_notifications' => ['sometimes', 'boolean'],
                'talent_updates' => ['sometimes', 'boolean'],
                'shoot_reminders' => ['sometimes', 'boolean'],
                'payment_alerts' => ['sometimes', 'boolean'],
                'system_updates' => ['sometimes', 'boolean'],
                'language' => ['nullable', 'string', 'max:120'],
                'timezone' => ['nullable', 'string', 'max:120'],
                'date_format' => ['nullable', 'string', 'max:50'],
                'time_format' => ['nullable', 'string', 'max:50'],
                'appearance' => ['nullable', 'string', 'max:50'],
                'background_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp'],
                'remove_background_image' => ['sometimes', 'boolean'],
            ]);

            $settings = AdminSetting::singleton();

            // Handle background image upload
            if ($request->has('remove_background_image') && $request->boolean('remove_background_image')) {
                // Remove background image
                if ($settings->background_image_path) {
                    Storage::disk('public')->delete($settings->background_image_path);
                    $validated['background_image_path'] = null;
                }
            } elseif ($request->hasFile('background_image')) {
                try {
                    $file = $request->file('background_image');

                    // Validate file is actually uploaded (not corrupted)
                    if (!$file->isValid()) {
                        return back()->with('error', 'The uploaded file is invalid or corrupted. Please try uploading again.');
                    }

                    // Delete old background image if exists
                    if ($settings->background_image_path) {
                        try {
                            Storage::disk('public')->delete($settings->background_image_path);
                        } catch (\Exception $deleteError) {
                            // Log but don't fail - old file might not exist
                            \Log::warning('Failed to delete old background image: ' . $deleteError->getMessage());
                        }
                    }

                    // Store new background image
                    $path = $file->store('backgrounds', 'public');
                    if (!$path) {
                        return back()->with('error', 'Failed to store the uploaded file. Please check storage permissions.');
                    }

                    $validated['background_image_path'] = $path;
                } catch (\Illuminate\Http\Exceptions\PostTooLargeException $e) {
                    $postMaxMB = round($this->parseSize(ini_get('post_max_size')) / 1024, 2);
                    return back()->with('error', "The uploaded file exceeds PHP's post_max_size limit of {$postMaxMB} MB. Please contact your server administrator to increase post_max_size in php.ini.");
                } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
                    return back()->with('error', 'The uploaded file could not be found. Please try uploading again.');
                } catch (\Illuminate\Contracts\Filesystem\FileExistsException $e) {
                    return back()->with('error', 'A file with this name already exists. Please rename your file and try again.');
                } catch (\Exception $e) {
                    // Catch all other file-related errors
                    $errorMessage = 'Failed to upload background image';
                    if (strpos($e->getMessage(), 'disk') !== false || strpos($e->getMessage(), 'storage') !== false) {
                        $errorMessage .= ': Storage error. Please check storage permissions.';
                    } elseif (strpos($e->getMessage(), 'permission') !== false) {
                        $errorMessage .= ': Permission denied. Please check file permissions.';
                    } else {
                        $errorMessage .= ': ' . $e->getMessage();
                    }
                    return back()->with('error', $errorMessage);
                }
            }

            // Ensure missing checkboxes are treated as false
            foreach ([
                'email_notifications',
                'push_notifications',
                'talent_updates',
                'shoot_reminders',
                'payment_alerts',
                'system_updates',
            ] as $flag) {
                $validated[$flag] = $request->boolean($flag);
            }

            // Remove file input from validated data before saving
            unset($validated['background_image'], $validated['remove_background_image']);

            $settings->fill($validated)->save();

            return back()->with('message', __('Settings updated successfully.'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation errors - collect all errors into a single message for SweetAlert
            $errorMessages = [];
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $errorMessages[] = $message;
                }
            }
            $errorMessage = implode(' ', $errorMessages);
            return back()->with('error', $errorMessage)->withInput();
        } catch (\Illuminate\Http\Exceptions\PostTooLargeException $e) {
            // Handle POST too large exception
            $postMaxMB = round($this->parseSize(ini_get('post_max_size')) / 1024, 2);
            return back()->with('error', "The uploaded file exceeds PHP's post_max_size limit of {$postMaxMB} MB. Please contact your server administrator to increase post_max_size in php.ini.");
        } catch (\Exception $e) {
            // Return general errors - ensure all errors are shown via SweetAlert
            $errorMessage = 'An error occurred: ' . $e->getMessage();

            // Add more context for file-related errors
            if (strpos($e->getMessage(), 'file') !== false ||
                strpos($e->getMessage(), 'upload') !== false ||
                strpos($e->getMessage(), 'storage') !== false ||
                strpos($e->getMessage(), 'image') !== false) {
                $errorMessage = 'File upload error: ' . $e->getMessage();
            }

            return back()->with('error', $errorMessage);
        }
    }
}
