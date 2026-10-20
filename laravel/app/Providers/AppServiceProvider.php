<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('front.*', function ($view) {
            $publicKeys = [
                'home_eyebrow',
                'home_heading',
                'home_intro',
                'footer_about',
                'contact_address',
                'contact_email',
                'site_name',
                'site_tagline',
                'contact_phone',
                'opening_hours',
                'seo_site_title',
                'seo_site_description',
                'seo_canonical_base_url',
                'seo_default_noindex',
                'seo_default_nofollow',
            ];

            $view->with('siteSettings', SiteSetting::query()
                ->whereIn('key', $publicKeys)
                ->pluck('value', 'key'));
        });
    }
}
