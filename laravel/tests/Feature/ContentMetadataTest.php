<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Gallery;
use App\Models\Habitat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContentMetadataTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_save_and_edit_habitat_content_metadata(): void
    {
        Storage::fake('legacy_uploads');
        $this->actingAs(User::factory()->create());

        $this->post(route('admin.habitats.store'), [
            'name' => 'Rainforest',
            'description' => 'A habitat description.',
            'short_description' => 'A lush habitat.',
            'climate' => 'Tropical',
            'location' => 'East wing',
            'geographical_range' => 'Equatorial regions.',
            'image_alt_text' => 'Rainforest canopy',
            'image_caption' => 'A dense canopy.',
            'image_credit' => 'Wildlife Kingdom',
            'meta_title' => 'Rainforest Habitat',
            'meta_description' => 'Explore this rainforest habitat.',
            'canonical_url' => 'https://example.test/habitats/rainforest',
            'display_order' => 2,
            'status' => 'published',
            'is_homepage_visible' => '1',
        ])->assertRedirect(route('admin.habitats.index'));

        $habitat = Habitat::where('name', 'Rainforest')->firstOrFail();
        $this->assertSame('Tropical', $habitat->climate);
        $this->assertSame('East wing', $habitat->location);
        $this->assertSame('Rainforest canopy', $habitat->image_alt_text);
        $this->get(route('admin.habitats.edit', $habitat))->assertOk()->assertSee('Rainforest Habitat');

        $this->put(route('admin.habitats.update', $habitat), [
            'name' => 'Rainforest',
            'description' => 'Updated habitat description.',
            'short_description' => 'Updated short text.',
            'climate' => 'Warm and humid',
            'location' => 'New wing',
            'image_alt_text' => 'Updated canopy',
            'meta_title' => 'Updated rainforest',
            'canonical_url' => 'https://example.test/habitats/rainforest',
            'display_order' => 3,
            'status' => 'draft',
            'is_homepage_visible' => '0',
        ])->assertRedirect(route('admin.habitats.index'));

        $this->assertDatabaseHas('habitats', [
            'id' => $habitat->id,
            'climate' => 'Warm and humid',
            'location' => 'New wing',
            'image_alt_text' => 'Updated canopy',
            'status' => 'draft',
            'is_homepage_visible' => 0,
        ]);
    }

    public function test_admin_can_save_and_edit_event_content_metadata(): void
    {
        Storage::fake('legacy_uploads');
        $this->actingAs(User::factory()->create());

        $this->post(route('admin.events.store'), [
            'title' => 'Keeper Talk',
            'description' => 'Meet the animal care team.',
            'location' => 'Education Centre',
            'event_date' => '2027-01-15',
            'event_time' => '10:00 AM',
            'image_alt_text' => 'Keeper speaking to visitors',
            'image_caption' => 'A keeper talk.',
            'image_credit' => 'Education team',
            'meta_title' => 'Keeper Talk Event',
            'meta_description' => 'Join a keeper talk.',
            'canonical_url' => 'https://example.test/events/keeper-talk',
            'display_order' => 1,
            'status' => 'published',
            'is_featured' => '1',
            'is_homepage_visible' => '1',
        ])->assertRedirect(route('admin.events.index'));

        $event = Event::where('title', 'Keeper Talk')->firstOrFail();
        $this->assertSame('Education Centre', $event->location);
        $this->assertSame('Keeper speaking to visitors', $event->image_alt_text);
        $this->assertTrue($event->is_featured);
        $this->get(route('admin.events.edit', $event))->assertOk()->assertSee('Keeper Talk Event');

        $this->put(route('admin.events.update', $event), [
            'title' => 'Keeper Talk',
            'description' => 'Updated event details.',
            'location' => 'Main Hall',
            'event_date' => '2027-01-16',
            'event_time' => '11:00 AM',
            'image_alt_text' => 'Updated talk image',
            'image_caption' => 'Updated caption.',
            'meta_title' => 'Updated Keeper Talk',
            'canonical_url' => 'https://example.test/events/keeper-talk',
            'display_order' => 2,
            'status' => 'draft',
            'is_featured' => '0',
            'is_homepage_visible' => '0',
        ])->assertRedirect(route('admin.events.index'));

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'location' => 'Main Hall',
            'image_alt_text' => 'Updated talk image',
            'status' => 'draft',
            'is_featured' => 0,
            'is_homepage_visible' => 0,
        ]);
    }

    public function test_admin_can_save_and_edit_gallery_image_metadata(): void
    {
        Storage::fake('legacy_uploads');
        $this->actingAs(User::factory()->create());
        $image = new UploadedFile(
            base_path('../assets/images/logo/wildlife-kingdom-favicon.png'),
            'gallery.png',
            'image/png',
            UPLOAD_ERR_OK,
            true
        );

        $this->post(route('admin.gallery.store'), [
            'title' => 'Macaw',
            'description' => 'A gallery item.',
            'category' => 'birds',
            'image' => $image,
            'image_alt_text' => 'Colourful macaw',
            'image_caption' => 'A close-up of a macaw.',
            'image_credit' => 'Photo team',
            'image_source' => 'Field archive',
            'display_order' => 2,
            'status' => 'published',
            'is_homepage_visible' => '1',
        ])->assertRedirect(route('admin.gallery.index'));

        $gallery = Gallery::where('title', 'Macaw')->firstOrFail();
        $this->assertSame('Colourful macaw', $gallery->image_alt_text);
        $this->assertSame('Field archive', $gallery->image_source);
        $this->get(route('admin.gallery.edit', $gallery))->assertOk()->assertSee('Colourful macaw');

        $this->put(route('admin.gallery.update', $gallery), [
            'title' => 'Macaw',
            'description' => 'Updated gallery item.',
            'category' => 'birds',
            'image_alt_text' => 'Updated macaw alt',
            'image_caption' => 'Updated caption.',
            'image_credit' => 'Updated credit',
            'image_source' => 'Updated source',
            'display_order' => 3,
            'status' => 'draft',
            'is_homepage_visible' => '0',
        ])->assertRedirect(route('admin.gallery.index'));

        $this->assertDatabaseHas('galleries', [
            'id' => $gallery->id,
            'image_alt_text' => 'Updated macaw alt',
            'image_source' => 'Updated source',
            'status' => 'draft',
            'is_homepage_visible' => 0,
        ]);
    }
}
