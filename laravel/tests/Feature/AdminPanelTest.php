<?php

namespace Tests\Feature;

use App\Models\Animal;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Habitat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        URL::forceRootUrl('http://localhost');
    }

    public function test_admin_can_log_in_and_view_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.test',
            'password' => Hash::make('secret-password'),
        ]);

        $loginPage = $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('Wildlife Kingdom Admin')
            ->assertSee('name="_token"', false);

        $sessionCookie = collect($loginPage->headers->getCookies())
            ->first(fn ($cookie) => $cookie->getName() === config('session.cookie'));
        $this->assertNotNull($sessionCookie);
        $this->assertSame('/', $sessionCookie->getPath());

        $this->withSession(['url.intended' => route('admin.animals.index')]);
        $this->post(route('admin.authenticate'), [
            'email' => $user->email,
            'password' => 'secret-password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Total Animals')
            ->assertSee('Total Habitats');
    }

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.animals.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.habitats.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.events.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.gallery.index'))->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_admin_is_redirected_to_admin_dashboard_from_login(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.login'))
            ->assertRedirect('/admin');
    }

    public function test_admin_can_log_out_and_return_to_the_login_form(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.test',
            'password' => Hash::make('secret-password'),
        ]);

        $loginForm = $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('Wildlife Kingdom Admin')
            ->assertSee('name="_token"', false);

        $this->post(route('admin.authenticate'), [
            'email' => $user->email,
            'password' => 'secret-password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('name="_token"', false);

        $this->get(route('admin.animals.index'))->assertOk();
        $this->get(route('admin.habitats.index'))->assertOk();
        $this->get(route('admin.events.index'))->assertOk();
        $this->get(route('admin.gallery.index'))->assertOk();
        $this->get(route('admin.dashboard'))->assertOk();

        $this->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));

        $this->assertGuest('web');
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('Wildlife Kingdom Admin')
            ->assertSee('name="_token"', false);
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_create_update_list_and_delete_animal(): void
    {
        Storage::fake('legacy_uploads');
        $this->actingAs(User::factory()->create());
        $habitat = Habitat::create([
            'name' => 'Savannah',
            'description' => 'Open grassland.',
            'status' => 'published',
        ]);
        $image = new UploadedFile(
            base_path('../assets/images/logo/wildlife-kingdom-favicon.png'),
            'lion.png',
            'image/png',
            UPLOAD_ERR_OK,
            true
        );

        $this->post(route('admin.animals.store'), [
            'name' => 'Test Lion',
            'scientific_name' => 'Panthera leo',
            'conservation_status' => 'Vulnerable',
            'category' => 'Mammals',
            'habitat_id' => $habitat->id,
            'description' => 'A test animal.',
            'status' => 'published',
            'is_featured' => '1',
            'image' => $image,
        ])->assertRedirect(route('admin.animals.index'));

        $animal = Animal::where('name', 'Test Lion')->firstOrFail();
        $this->assertSame('Vulnerable', $animal->conservation_status);
        $this->assertSame(basename($animal->image), $animal->image);
        Storage::disk('legacy_uploads')->assertExists('animals/'.$animal->image);
        $this->get(route('admin.animals.index'))->assertOk()->assertSee('Test Lion');
        $this->get(route('admin.animals.edit', $animal))->assertOk();

        $this->put(route('admin.animals.update', $animal), [
            'name' => 'Updated Lion',
            'scientific_name' => 'Panthera leo',
            'conservation_status' => 'Endangered',
            'category' => 'Mammals',
            'habitat_id' => $habitat->id,
            'description' => 'Updated animal description.',
            'status' => 'draft',
        ])->assertRedirect(route('admin.animals.index'));

        $this->assertDatabaseHas('animals', ['id' => $animal->id, 'name' => 'Updated Lion']);
        $this->delete(route('admin.animals.destroy', $animal))->assertRedirect(route('admin.animals.index'));
        $this->assertDatabaseMissing('animals', ['id' => $animal->id]);
        Storage::disk('legacy_uploads')->assertMissing('animals/'.$animal->image);
    }

    public function test_admin_can_create_update_list_and_delete_habitat(): void
    {
        Storage::fake('legacy_uploads');
        $this->actingAs(User::factory()->create());
        $image = new UploadedFile(
            base_path('../assets/images/logo/wildlife-kingdom-favicon.png'),
            'habitat.png',
            'image/png',
            UPLOAD_ERR_OK,
            true
        );

        $this->post(route('admin.habitats.store'), [
            'name' => 'Test Habitat',
            'short_description' => 'A short description.',
            'description' => 'A habitat description.',
            'status' => 'published',
            'image' => $image,
        ])->assertRedirect(route('admin.habitats.index'));

        $habitat = Habitat::where('name', 'Test Habitat')->firstOrFail();
        $this->assertSame(basename($habitat->image), $habitat->image);
        Storage::disk('legacy_uploads')->assertExists('habitats/'.$habitat->image);
        $this->get(route('admin.habitats.index'))->assertOk()->assertSee('Test Habitat');
        $this->get(route('admin.habitats.edit', $habitat))->assertOk();

        $this->put(route('admin.habitats.update', $habitat), [
            'name' => 'Updated Habitat',
            'short_description' => 'Updated short description.',
            'description' => 'Updated habitat description.',
            'status' => 'draft',
        ])->assertRedirect(route('admin.habitats.index'));

        $this->assertDatabaseHas('habitats', ['id' => $habitat->id, 'name' => 'Updated Habitat']);
        $this->delete(route('admin.habitats.destroy', $habitat))->assertRedirect(route('admin.habitats.index'));
        $this->assertDatabaseMissing('habitats', ['id' => $habitat->id]);
        Storage::disk('legacy_uploads')->assertMissing('habitats/'.$habitat->image);
    }

    public function test_admin_can_create_update_list_and_delete_event(): void
    {
        Storage::fake('legacy_uploads');
        $this->actingAs(User::factory()->create());
        $image = new UploadedFile(
            base_path('../assets/images/logo/wildlife-kingdom-favicon.png'),
            'event.png',
            'image/png',
            UPLOAD_ERR_OK,
            true
        );

        $this->post(route('admin.events.store'), [
            'title' => 'Test Event',
            'description' => 'An event description.',
            'location' => 'Education Centre',
            'event_date' => '2027-01-15',
            'event_time' => '08:00 AM',
            'status' => 'published',
            'image' => $image,
        ])->assertRedirect(route('admin.events.index'));

        $event = Event::where('title', 'Test Event')->firstOrFail();
        $this->assertSame(basename($event->image), $event->image);
        Storage::disk('legacy_uploads')->assertExists('events/'.$event->image);
        $this->get(route('admin.events.index'))->assertOk()->assertSee('Test Event');
        $this->get(route('admin.events.edit', $event))->assertOk();

        $this->put(route('admin.events.update', $event), [
            'title' => 'Updated Event',
            'description' => 'Updated event description.',
            'location' => 'Main Hall',
            'event_date' => '2027-01-16',
            'event_time' => '09:00 AM',
            'status' => 'draft',
        ])->assertRedirect(route('admin.events.index'));

        $this->assertDatabaseHas('events', ['id' => $event->id, 'title' => 'Updated Event']);
        $this->delete(route('admin.events.destroy', $event))->assertRedirect(route('admin.events.index'));
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
        Storage::disk('legacy_uploads')->assertMissing('events/'.$event->image);
    }

    public function test_admin_can_create_update_list_and_delete_gallery_image(): void
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
            'title' => 'Test Gallery Image',
            'description' => 'A gallery description.',
            'category' => 'wildlife',
            'status' => 'published',
            'image' => $image,
        ])->assertRedirect(route('admin.gallery.index'));

        $gallery = Gallery::where('title', 'Test Gallery Image')->firstOrFail();
        $this->assertSame(basename($gallery->image), $gallery->image);
        Storage::disk('legacy_uploads')->assertExists('gallery/'.$gallery->image);
        $this->get(route('admin.gallery.index'))->assertOk()->assertSee('Test Gallery Image');
        $this->get(route('admin.gallery.edit', $gallery))->assertOk();

        $this->put(route('admin.gallery.update', $gallery), [
            'title' => 'Updated Gallery Image',
            'description' => 'Updated gallery description.',
            'category' => 'habitats',
            'status' => 'draft',
        ])->assertRedirect(route('admin.gallery.index'));

        $this->assertDatabaseHas('galleries', ['id' => $gallery->id, 'title' => 'Updated Gallery Image']);
        $this->delete(route('admin.gallery.destroy', $gallery))->assertRedirect(route('admin.gallery.index'));
        $this->assertDatabaseMissing('galleries', ['id' => $gallery->id]);
        Storage::disk('legacy_uploads')->assertMissing('gallery/'.$gallery->image);
    }
}
