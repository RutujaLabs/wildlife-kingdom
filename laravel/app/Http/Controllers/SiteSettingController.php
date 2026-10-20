<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SiteSettingController extends Controller
{
    public function edit()
    {
        $settings = $this->settings($this->pageDefaults());

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $this->save($request, [
            'home_eyebrow' => ['required', 'string', 'max:100'],
            'home_heading' => ['required', 'string', 'max:180'],
            'home_intro' => ['required', 'string', 'max:1000'],
            'footer_about' => ['required', 'string', 'max:2000'],
            'contact_address' => ['required', 'string', 'max:500'],
            'contact_email' => ['required', 'email', 'max:255'],
        ]);

        return redirect()->route('admin.settings.edit')->with('success', 'Website content updated successfully.');
    }

    public function editSeo()
    {
        $settings = $this->settings($this->seoDefaults());

        return view('admin.settings.seo', compact('settings'));
    }

    public function updateSeo(Request $request)
    {
        $this->save($request, [
            'seo_site_title' => ['required', 'string', 'max:70'],
            'seo_site_description' => ['required', 'string', 'max:320'],
            'seo_canonical_base_url' => ['nullable', 'url', 'max:2048'],
            'seo_default_noindex' => ['sometimes', 'boolean'],
            'seo_default_nofollow' => ['sometimes', 'boolean'],
        ], ['seo_default_noindex', 'seo_default_nofollow']);

        return redirect()->route('admin.settings.seo.edit')->with('success', 'SEO settings updated successfully.');
    }

    public function editApi()
    {
        $settings = $this->settings([
            'ai_provider' => 'openai',
            'ai_api_key_encrypted' => null,
            'ai_integration_enabled' => false,
        ]);
        $settings['api_key_configured'] = filled($settings['ai_api_key_encrypted']);
        unset($settings['ai_api_key_encrypted']);

        return view('admin.settings.api', compact('settings'));
    }

    public function updateApi(Request $request)
    {
        $validated = $request->validate([
            'ai_provider' => ['required', 'in:openai,anthropic,google'],
            'ai_api_key' => ['nullable', 'string', 'max:4096'],
            'ai_integration_enabled' => ['sometimes', 'boolean'],
            'remove_api_key' => ['sometimes', 'boolean'],
        ]);

        $currentKey = SiteSetting::query()->where('key', 'ai_api_key_encrypted')->value('value');
        $settingsToSave = [
            'ai_provider' => $validated['ai_provider'],
            'ai_integration_enabled' => $request->boolean('ai_integration_enabled'),
        ];

        if ($request->boolean('remove_api_key')) {
            $settingsToSave['ai_api_key_encrypted'] = null;
            $settingsToSave['ai_integration_enabled'] = false;
        } elseif (filled($validated['ai_api_key'] ?? null)) {
            $settingsToSave['ai_api_key_encrypted'] = Crypt::encryptString($validated['ai_api_key']);
        } elseif ($currentKey !== null) {
            Crypt::decryptString($currentKey);
        }

        $hasKeyAfterUpdate = array_key_exists('ai_api_key_encrypted', $settingsToSave)
            ? filled($settingsToSave['ai_api_key_encrypted'])
            : filled($currentKey);
        if ($settingsToSave['ai_integration_enabled'] && ! $hasKeyAfterUpdate) {
            throw ValidationException::withMessages([
                'ai_api_key' => 'Enter an API key before enabling backend integrations.',
            ]);
        }

        DB::transaction(function () use ($settingsToSave) {
            foreach ($settingsToSave as $key => $value) {
                SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        });

        return redirect()->route('admin.settings.api.edit')->with('success', 'API settings updated successfully.');
    }

    public function editWebsite()
    {
        $settings = $this->settings($this->websiteDefaults());

        return view('admin.settings.website', compact('settings'));
    }

    public function updateWebsite(Request $request)
    {
        $this->save($request, [
            'site_name' => ['required', 'string', 'max:150'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'contact_address' => ['required', 'string', 'max:500'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'opening_hours' => ['nullable', 'string', 'max:500'],
        ]);

        return redirect()->route('admin.settings.website.edit')->with('success', 'Website settings updated successfully.');
    }

    protected function settings(array $defaults): array
    {
        $stored = SiteSetting::query()
            ->whereIn('key', array_keys($defaults))
            ->pluck('value', 'key')
            ->all();

        return array_merge($defaults, $stored);
    }

    protected function save(Request $request, array $rules, array $checkboxes = []): void
    {
        $validated = $request->validate($rules);
        foreach ($checkboxes as $checkbox) {
            $validated[$checkbox] = $request->boolean($checkbox);
        }

        DB::transaction(function () use ($validated) {
            foreach ($validated as $key => $value) {
                SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        });
    }

    protected function pageDefaults(): array
    {
        return [
            'home_eyebrow' => 'Wildlife Experience',
            'home_heading' => 'Discover the rhythm of the wild.',
            'home_intro' => 'Explore majestic species, rich habitats, seasonal events and unforgettable conservation stories at Wildlife Kingdom.',
            'footer_about' => 'A sanctuary for wonder, conservation, and discovery. Meet incredible animals, explore living habitats and connect with the natural world — every visit supports the wildlife we protect.',
            'contact_address' => "123 Rainforest Road\nWildlife County",
            'contact_email' => 'hello@wildlifekingdom.com',
        ];
    }

    protected function seoDefaults(): array
    {
        return [
            'seo_site_title' => 'Wildlife Kingdom',
            'seo_site_description' => 'Explore majestic species, rich habitats, and conservation stories at Wildlife Kingdom.',
            'seo_canonical_base_url' => '',
            'seo_default_noindex' => false,
            'seo_default_nofollow' => false,
        ];
    }

    protected function websiteDefaults(): array
    {
        return [
            'site_name' => 'Wildlife Kingdom',
            'site_tagline' => 'A sanctuary for wonder, conservation, and discovery.',
            'contact_address' => "123 Rainforest Road\nWildlife County",
            'contact_email' => 'hello@wildlifekingdom.com',
            'contact_phone' => '',
            'opening_hours' => '',
        ];
    }
}
