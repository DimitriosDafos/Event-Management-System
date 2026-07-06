<?php
namespace App\Http\Controllers;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandingController extends Controller
{
    public function index()
    {
        $settings = [
            'brand_name'    => Setting::get('brand_name', 'disclosure'),
            'brand_tagline' => Setting::get('brand_tagline', ''),
            'brand_logo'    => Setting::get('brand_logo', ''),
            'footer_text'   => Setting::get('footer_text', 'event-team · gemeinnützig'),
        ];
        return view('branding.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'brand_name'    => 'required|string|max:80',
            'brand_tagline' => 'nullable|string|max:160',
            'footer_text'   => 'nullable|string|max:200',
            'logo'          => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
        ]);

        Setting::set('brand_name',    $request->brand_name);
        Setting::set('brand_tagline', $request->brand_tagline);
        Setting::set('footer_text',   $request->footer_text);

        if ($request->hasFile('logo')) {
            // Delete old logo
            $old = Setting::get('brand_logo');
            if ($old) Storage::disk('public')->delete($old);

            $path = $request->file('logo')->store('branding', 'public');
            Setting::set('brand_logo', $path);
        }

        if ($request->boolean('remove_logo')) {
            $old = Setting::get('brand_logo');
            if ($old) Storage::disk('public')->delete($old);
            Setting::set('brand_logo', null);
        }

        return back()->with('success', 'Branding gespeichert.');
    }
}
