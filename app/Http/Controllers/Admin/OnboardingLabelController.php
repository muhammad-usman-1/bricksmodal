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
        $talentLabels = $this->getBilingualLabels('talent');
        $adminSidebarLabels = $this->getBilingualLabels('admin_sidebar');
        $adminHomeLabels = $this->getBilingualLabels('admin_home');
        $adminTalentsLabels = $this->getBilingualLabels('admin_talents');
        $adminTalentProfileLabels = $this->getBilingualLabels('admin_talent_profile');

        // Load settings
        $settingsPath = resource_path('lang/label_settings.php');
        $settings = File::exists($settingsPath) ? include $settingsPath : ['onboarding' => true, 'casting' => true, 'talent' => true, 'admin_sidebar' => true, 'admin_home' => true, 'admin_talents' => true, 'admin_talent_profile' => true];

        return view('admin.onboarding-labels.index', compact('onboardingLabels', 'castingLabels', 'talentLabels', 'adminSidebarLabels', 'adminHomeLabels', 'adminTalentsLabels', 'adminTalentProfileLabels', 'settings'));
    }

    public function update(Request $request)
    {
        // Handle settings update
        if ($request->has('settings')) {
            $settings = [
                'onboarding' => $request->has('settings.onboarding'), // Checkbox presence means true
                'casting' => $request->has('settings.casting'),
                'talent' => $request->has('settings.talent'),
                'admin_sidebar' => $request->has('settings.admin_sidebar'),
                'admin_home' => $request->has('settings.admin_home'),
                'admin_talents' => $request->has('settings.admin_talents'),
                'admin_talent_profile' => $request->has('settings.admin_talent_profile'),
            ];
            $this->saveSettings($settings);
        }

        if ($request->has('onboarding')) {
            $this->processUpdate('onboarding', $request->input('onboarding'));
        }

        if ($request->has('casting')) {
            $this->processUpdate('casting', $request->input('casting'));
        }

        if ($request->has('talent')) {
            $this->processUpdate('talent', $request->input('talent'));
        }

        if ($request->has('admin_sidebar')) {
            $this->processUpdate('admin_sidebar', $request->input('admin_sidebar'));
        }

        if ($request->has('admin_home')) {
            $this->processUpdate('admin_home', $request->input('admin_home'));
        }

        if ($request->has('admin_talents')) {
            $this->processUpdate('admin_talents', $request->input('admin_talents'));
        }

        if ($request->has('admin_talent_profile')) {
            $this->processUpdate('admin_talent_profile', $request->input('admin_talent_profile'));
        }

        return redirect()->route('admin.onboarding-labels.index')->with('success', 'Changes saved successfully.');
    }

    private function saveSettings($settings)
    {
        $path = resource_path('lang/label_settings.php');
        $content = "<?php\n\nreturn [\n";
        $content .= "    'onboarding' => " . ($settings['onboarding'] ?? true ? 'true' : 'false') . ",\n";
        $content .= "    'casting' => " . ($settings['casting'] ?? true ? 'true' : 'false') . ",\n";
        $content .= "    'talent' => " . ($settings['talent'] ?? true ? 'true' : 'false') . ",\n";
        $content .= "    'admin_sidebar' => " . ($settings['admin_sidebar'] ?? true ? 'true' : 'false') . ",\n";
        $content .= "    'admin_home' => " . ($settings['admin_home'] ?? true ? 'true' : 'false') . ",\n";
        $content .= "    'admin_talents' => " . ($settings['admin_talents'] ?? true ? 'true' : 'false') . ",\n";
        $content .= "    'admin_talent_profile' => " . ($settings['admin_talent_profile'] ?? true ? 'true' : 'false') . ",\n";
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
            // Escape single quotes and backslashes properly
            $safeValue = addslashes($value);
            $content .= "    '{$key}' => '{$safeValue}',\n";
        }
        
        $content .= "];\n";
        
        File::put($path, $content);
    }
}
