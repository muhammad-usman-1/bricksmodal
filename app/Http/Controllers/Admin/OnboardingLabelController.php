<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class OnboardingLabelController extends Controller
{
    public function index()
    {
        $onboardingLabels = $this->getBilingualLabels('onboarding');
        $castingLabels = $this->getBilingualLabels('casting');

        // Load settings
        $settingsPath = resource_path('lang/label_settings.php');
        $settings = File::exists($settingsPath) ? include $settingsPath : ['onboarding' => true, 'casting' => true];

        return view('admin.onboarding-labels.index', compact('onboardingLabels', 'castingLabels', 'settings'));
    }

    public function update(Request $request)
    {
        // Handle settings update
        if ($request->has('settings')) {
            $settings = [
                'onboarding' => $request->has('settings.onboarding'), // Checkbox presence means true
                'casting' => $request->has('settings.casting'),
            ];
            $this->saveSettings($settings);
        }

        if ($request->has('onboarding')) {
            $this->processUpdate('onboarding', $request->input('onboarding'));
        }

        if ($request->has('casting')) {
            $this->processUpdate('casting', $request->input('casting'));
        }

        return redirect()->route('admin.onboarding-labels.index')->with('success', 'Changes saved successfully.');
    }

    private function saveSettings($settings)
    {
        $path = resource_path('lang/label_settings.php');
        $content = "<?php\n\nreturn [\n";
        $content .= "    'onboarding' => " . ($settings['onboarding'] ? 'true' : 'false') . ",\n";
        $content .= "    'casting' => " . ($settings['casting'] ? 'true' : 'false') . ",\n";
        $content .= "];\n";
        
        File::put($path, $content);
    }

    private function getBilingualLabels($file)
    {
        $enLabels = $this->getLabels('en', $file);
        $arLabels = $this->getLabels('ar', $file);

        $allKeys = array_unique(array_merge(array_keys($enLabels), array_keys($arLabels)));
        $labels = [];

        foreach ($allKeys as $key) {
            $labels[$key] = [
                'en' => $enLabels[$key] ?? '',
                'ar' => $arLabels[$key] ?? '',
            ];
        }

        return $labels;
    }

    private function processUpdate($file, $data)
    {
        $currentLabels = $this->getLabels('ar', $file);
        
        foreach ($data as $key => $value) {
            $currentLabels[$key] = $value;
        }

        $this->saveLabels('ar', $currentLabels, $file);
    }

    private function getLabels($locale, $file)
    {
        $path = resource_path("lang/{$locale}/{$file}.php");
        if (File::exists($path)) {
            return include $path;
        }
        return []; 
    }

    private function saveLabels($locale, $labels, $file)
    {
        $path = resource_path("lang/{$locale}/{$file}.php");
        $content = "<?php\n\nreturn [\n";
        
        foreach ($labels as $key => $value) {
            // Escape single quotes
            $safeValue = str_replace("'", "\'", $value);
            $content .= "    '{$key}' => '{$safeValue}',\n";
        }
        
        $content .= "];\n";
        
        File::put($path, $content);
    }
}
