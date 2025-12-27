<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Outfit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class OutfitController extends Controller
{
    /**
     * Ensure the outfits directory exists with proper permissions
     */
    private function ensureOutfitsDirectory()
    {
        $directory = public_path('images/outfits');

        // Create parent directories if they don't exist
        $parentDir = public_path('images');
        if (!file_exists($parentDir)) {
            @mkdir($parentDir, 0755, true);
        }

        if (!file_exists($directory)) {
            if (!@mkdir($directory, 0755, true)) {
                throw new \Exception('Unable to create directory: ' . $directory . '. Please check directory permissions.');
            }
        }

        // Ensure directory is writable
        if (!is_writable($directory)) {
            if (!@chmod($directory, 0755)) {
                throw new \Exception('Directory exists but is not writable: ' . $directory . '. Please check directory permissions.');
            }
        }

        return $directory;
    }

    public function index()
    {
        $outfits = Outfit::orderBy('category')->orderBy('sort_order')->get();
        return view('admin.outfits.index', compact('outfits'));
    }

    public function create()
    {
        return view('admin.outfits.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:male,female,child',
            'sub_category' => 'required|in:top,bottom,traditional',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            try {
                $this->ensureOutfitsDirectory();
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images/outfits'), $imageName);
                $data['image'] = '/images/outfits/' . $imageName;
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['image' => 'Failed to upload image: ' . $e->getMessage()]);
            }
        }

        Outfit::create($data);

        return redirect()->route('admin.outfits.index')->with('message', 'Outfit created successfully.');
    }

    public function edit(Outfit $outfit)
    {
        return view('admin.outfits.edit', compact('outfit'));
    }

    public function update(Request $request, Outfit $outfit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:male,female,child',
            'sub_category' => 'required|in:top,bottom,traditional',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            try {
                // Delete old image if exists
                if ($outfit->image && file_exists(public_path($outfit->image))) {
                    @unlink(public_path($outfit->image));
                }

                $this->ensureOutfitsDirectory();
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images/outfits'), $imageName);
                $data['image'] = '/images/outfits/' . $imageName;
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['image' => 'Failed to upload image: ' . $e->getMessage()]);
            }
        }

        $outfit->update($data);

        return redirect()->route('admin.outfits.index')->with('message', 'Outfit updated successfully.');
    }

    public function destroy(Outfit $outfit)
    {
        if ($outfit->image && file_exists(public_path($outfit->image))) {
            @unlink(public_path($outfit->image));
        }
        $outfit->delete();

        return redirect()->route('admin.outfits.index')->with('message', 'Outfit deleted successfully.');
    }
}
