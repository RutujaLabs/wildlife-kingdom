<?php

namespace Tests\Feature;

use App\Models\Animal;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Habitat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_laravel_homepage_redirects_to_the_php_public_homepage(): void
    {
        $this->get(route('home'))
            ->assertRedirect(url('/public_html/index.php'));
    }

    public function test_public_animal_pages_only_show_published_animals(): void
    {
        $publishedAnimal = Animal::create([
            'name' => 'Published Giraffe',
            'status' => 'published',
        ]);
        $draftAnimal = Animal::create([
            'name' => 'Draft Giraffe',
            'status' => 'draft',
        ]);

        $this->get(route('animals'))
            ->assertOk()
            ->assertSee('Published Giraffe')
            ->assertDontSee('Draft Giraffe');

        $this->get(route('animal.show', $publishedAnimal))
            ->assertOk()
            ->assertSee('Published Giraffe');

        $this->get(route('animal.show', $draftAnimal))
            ->assertNotFound();
    }

    public function test_public_habitat_events_and_gallery_pages_only_show_published_content(): void
    {
        Habitat::create([
            'name' => 'Published Forest',
            'status' => 'published',
        ]);
        Habitat::create([
            'name' => 'Draft Forest',
            'status' => 'draft',
        ]);
        Event::create([
            'title' => 'Published Tour',
            'event_date' => now()->addDays(3)->toDateString(),
            'status' => 'published',
        ]);
        Event::create([
            'title' => 'Draft Tour',
            'event_date' => now()->addDays(4)->toDateString(),
            'status' => 'draft',
        ]);
        Gallery::create([
            'title' => 'Published Photo',
            'status' => 'published',
        ]);
        Gallery::create([
            'title' => 'Draft Photo',
            'status' => 'draft',
        ]);

        $this->get(route('habitats'))
            ->assertOk()
            ->assertSee('Published Forest')
            ->assertDontSee('Draft Forest');

        $this->get(route('events'))
            ->assertOk()
            ->assertSee('Published Tour')
            ->assertDontSee('Draft Tour');

        $this->get(route('gallery'))
            ->assertOk()
            ->assertSee('Published Photo')
            ->assertDontSee('Draft Photo');
    }
}
