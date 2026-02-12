<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CastingRequirement;
use App\Models\TalentProfile;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth('admin')->user();

        $q = trim((string) $request->query('q', ''));
        if (mb_strlen($q) > 120) {
            $q = mb_substr($q, 0, 120);
        }

        $canSeeTalents = $admin && ($admin->isSuperAdmin() || $admin->hasModulePermission('talent_management'));
        $canSeeShoots = $admin && ($admin->isSuperAdmin() || $admin->hasModulePermission('project_management'));

        // If search query is numeric, check if it's a talent ID and redirect directly
        if ($q !== '' && $canSeeTalents && is_numeric($q)) {
            $talentProfile = TalentProfile::find($q);
            if ($talentProfile) {
                return redirect()->route('admin.talent-profiles.show', $talentProfile->id);
            }
        }

        $talents = collect();
        $shoots = collect();

        if ($q !== '' && $canSeeTalents) {
            $talents = TalentProfile::query()
                ->with('user:id,email')
                ->where(function ($query) use ($q) {
                    $query->where('id', $q) // Search by ID
                        ->orWhere('display_name', 'like', '%' . $q . '%')
                        ->orWhere('first_name', 'like', '%' . $q . '%')
                        ->orWhere('last_name', 'like', '%' . $q . '%')
                        ->orWhere('legal_name', 'like', '%' . $q . '%')
                        ->orWhere('mobile_number', 'like', '%' . $q . '%')
                        ->orWhere('whatsapp_number', 'like', '%' . $q . '%')
                        ->orWhereHas('user', function ($u) use ($q) {
                            $u->where('email', 'like', '%' . $q . '%');
                        });
                })
                ->latest()
                ->limit(20)
                ->get();
        }

        if ($q !== '' && $canSeeShoots) {
            $shoots = CastingRequirement::query()
                ->select(['id', 'project_name', 'client_name', 'location', 'shoot_date_time', 'status'])
                ->where(function ($query) use ($q) {
                    $query->where('project_name', 'like', '%' . $q . '%')
                        ->orWhere('client_name', 'like', '%' . $q . '%')
                        ->orWhere('location', 'like', '%' . $q . '%');
                })
                ->orderByDesc('id')
                ->limit(20)
                ->get();
        }

        return view('admin.search.index', [
            'q' => $q,
            'talents' => $talents,
            'shoots' => $shoots,
            'canSeeTalents' => $canSeeTalents,
            'canSeeShoots' => $canSeeShoots,
        ]);
    }
}



