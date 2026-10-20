<?php

namespace Tests\Feature;

use App\Models\Animal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminContentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_preview_drafts_and_public_routes_still_hide_them(): void
    {
        $animal = Animal::create([
            'name' => 'Preview Leopard',
            'status' => 'draft',
            'description' => 'Draft profile visible only in preview.',
        ]);

        $this->get(route('admin.animals.preview', $animal))
            ->assertRedirect(route('admin.login'));
        $this->get(route('animal.show', $animal))->assertNotFound();

        $this->actingAs(User::factory()->create())
            ->get(route('admin.animals.preview', $animal))
            ->assertOk()
            ->assertSee('Draft profile visible only in preview.')
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false)
            ->assertSee(route('animal.show', $animal), false);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.animals.edit', $animal))
            ->assertOk()
            ->assertSee(route('admin.animals.preview', $animal), false);
    }

    public function test_admin_can_manage_site_copy_and_sidebar_destinations_remain_available(): void
    {
        $this->get(route('admin.settings.edit'))->assertRedirect(route('admin.login'));

        $this->actingAs(User::factory()->create())
            ->get(route('admin.settings.edit'))
            ->assertOk()
            ->assertSee('Pages &amp; Content', false)
            ->assertSee('home_heading')
            ->assertSee('contact_email');

        $this->put(route('admin.settings.update'), [
            'home_eyebrow' => 'Conservation',
            'home_heading' => 'Discover Wildlife Kingdom',
            'home_intro' => 'An updated introduction.',
            'footer_about' => 'An updated footer message.',
            'contact_address' => '10 Nature Road, Green County',
            'contact_email' => 'hello@example.test',
        ])->assertRedirect(route('admin.settings.edit'));

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Discover Wildlife Kingdom')
            ->assertSee('An updated introduction.')
            ->assertSee('An updated footer message.')
            ->assertSee('hello@example.test');

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee(route('admin.animals.index'), false)
            ->assertSee(route('admin.habitats.index'), false)
            ->assertSee(route('admin.events.index'), false)
            ->assertSee(route('admin.gallery.index'), false)
            ->assertSee(route('admin.settings.edit'), false)
            ->assertSee(route('admin.settings.seo.edit'), false)
            ->assertSee(route('admin.settings.api.edit'), false)
            ->assertSee(route('admin.settings.website.edit'), false)
            ->assertSeeInOrder([
                'Pages & Content',
                'SEO Settings',
                'API Settings',
                'Website Settings',
                'Logout',
            ]);
    }

    public function test_all_settings_pages_are_authenticated_and_render_from_sidebar_routes(): void
    {
        $pages = [
            'admin.settings.edit' => 'Pages &amp; Content',
            'admin.settings.seo.edit' => 'SEO Settings',
            'admin.settings.api.edit' => 'API Settings',
            'admin.settings.website.edit' => 'Website Settings',
        ];

        foreach ($pages as $route => $label) {
            $this->get(route($route))->assertRedirect(route('admin.login'));
        }

        $this->actingAs(User::factory()->create());
        foreach ($pages as $route => $label) {
            $this->get(route($route))
                ->assertOk()
                ->assertSee($label, false);
        }
    }

    public function test_seo_and_website_settings_are_saved_and_used_by_the_public_layout(): void
    {
        $this->actingAs(User::factory()->create());

        $this->put(route('admin.settings.seo.update'), [
            'seo_site_title' => 'Wildlife Kingdom SEO',
            'seo_site_description' => 'Explore wildlife and conservation.',
            'seo_canonical_base_url' => 'https://wildlife.example',
            'seo_default_noindex' => '1',
            'seo_default_nofollow' => '0',
        ])->assertRedirect(route('admin.settings.seo.edit'));

        $this->put(route('admin.settings.website.update'), [
            'site_name' => 'Kingdom Wildlife',
            'site_tagline' => 'Protecting the wild.',
            'contact_address' => '12 Forest Lane',
            'contact_email' => 'contact@example.test',
            'contact_phone' => '555-0100',
            'opening_hours' => 'Daily, 9am to 5pm',
        ])->assertRedirect(route('admin.settings.website.edit'));

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('name="description" content="Explore wildlife and conservation."', false)
            ->assertSee('rel="canonical" href="https://wildlife.example/"', false)
            ->assertSee('name="robots" content="noindex, follow"', false)
            ->assertSee('Kingdom Wildlife')
            ->assertSee('Protecting the wild.')
            ->assertSee('contact@example.test')
            ->assertSee('555-0100')
            ->assertSee('Daily, 9am to 5pm');
    }

    public function test_api_credentials_are_encrypted_private_and_can_be_removed(): void
    {
        $this->actingAs(User::factory()->create());
        $secret = 'test-provider-secret-123';

        $this->put(route('admin.settings.api.update'), [
            'ai_provider' => 'openai',
            'ai_api_key' => $secret,
            'ai_integration_enabled' => '1',
        ])->assertRedirect(route('admin.settings.api.edit'));

        $stored = \App\Models\SiteSetting::query()->where('key', 'ai_api_key_encrypted')->value('value');
        $this->assertNotSame($secret, $stored);
        $this->assertSame($secret, \Illuminate\Support\Facades\Crypt::decryptString($stored));

        $this->get(route('admin.settings.api.edit'))
            ->assertOk()
            ->assertSee('Configured')
            ->assertDontSee($secret)
            ->assertDontSee($stored);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee($secret)
            ->assertDontSee($stored);

        $this->put(route('admin.settings.api.update'), [
            'ai_provider' => 'openai',
            'ai_integration_enabled' => '1',
            'remove_api_key' => '1',
        ])->assertRedirect(route('admin.settings.api.edit'));

        $this->assertNull(\App\Models\SiteSetting::query()->where('key', 'ai_api_key_encrypted')->value('value'));
    }

    public function test_api_integration_cannot_be_enabled_without_a_key(): void
    {
        $this->actingAs(User::factory()->create())
            ->from(route('admin.settings.api.edit'))
            ->put(route('admin.settings.api.update'), [
                'ai_provider' => 'openai',
                'ai_integration_enabled' => '1',
            ])
            ->assertRedirect(route('admin.settings.api.edit'))
            ->assertSessionHasErrors('ai_api_key');
    }
}
