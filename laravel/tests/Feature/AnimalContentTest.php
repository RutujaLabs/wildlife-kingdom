<?php

namespace Tests\Feature;

use App\Models\Animal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AnimalContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_save_and_edit_extended_animal_content(): void
    {
        Storage::fake('legacy_uploads');
        $this->actingAs(User::factory()->create());
        $this->get(route('admin.animals.create'))
            ->assertOk()
            ->assertSee('name="slug"', false)
            ->assertSee('name="short_description"', false)
            ->assertSee('name="kingdom"', false)
            ->assertSee('name="animal_class"', false)
            ->assertSee('name="animal_order"', false)
            ->assertSee('name="geographical_range"', false)
            ->assertSee('name="native_countries"', false)
            ->assertSee('name="average_weight"', false)
            ->assertSee('name="behaviour"', false)
            ->assertSee('name="population_information"', false)
            ->assertSee('name="image_alt_text"', false)
            ->assertSee('name="meta_title"', false)
            ->assertSee('name="canonical_url"', false)
            ->assertSee('name="social_image"', false)
            ->assertSee('name="noindex"', false)
            ->assertSee('name="new_faqs[0][question]"', false)
            ->assertSee('name="related_animals[]"', false)
            ->assertSee('name="new_gallery_images[0][file]"', false);
        $related = Animal::create(['name' => 'Related Otter', 'status' => 'published']);
        $galleryImage = new UploadedFile(
            base_path('../assets/images/logo/wildlife-kingdom-favicon.png'),
            'otter-gallery.png',
            'image/png',
            UPLOAD_ERR_OK,
            true
        );

        $this->post(route('admin.animals.store'), [
            'name' => 'River Otter',
            'scientific_name' => 'Lutra lutra',
            'slug' => 'river-otter',
            'short_description' => 'A short otter profile.',
            'conservation_status' => 'Near Threatened',
            'category' => 'Mammals',
            'description' => 'A complete otter profile.',
            'kingdom' => 'Animalia',
            'animal_class' => 'Mammalia',
            'animal_order' => 'Carnivora',
            'family' => 'Mustelidae',
            'genus' => 'Lutra',
            'species' => 'Lutra lutra',
            'habitat' => 'Freshwater rivers',
            'geographical_range' => 'Across Europe and Asia.',
            'native_countries' => 'United Kingdom, France',
            'distribution' => 'Coastal and inland waterways.',
            'diet' => 'Fish and invertebrates',
            'lifespan' => '8-12 years',
            'average_weight' => '7-12 kg',
            'average_height_length' => '57-95 cm',
            'speed' => 'Up to 12 km/h',
            'behaviour' => 'Mostly solitary.',
            'reproduction' => 'One litter per year.',
            'ecological_role' => 'Aquatic predator.',
            'population_information' => 'Population varies by region.',
            'major_threats' => 'Water pollution.',
            'conservation_efforts' => 'River restoration.',
            'fun_fact' => 'Otters have dense fur.',
            'image_alt_text' => 'Otter swimming in a river',
            'image_caption' => 'An otter in freshwater.',
            'image_credit' => 'Wildlife Kingdom',
            'image_source' => 'Field archive',
            'meta_title' => 'River Otter Facts',
            'meta_description' => 'Learn about river otters and their habitat.',
            'canonical_url' => 'https://example.test/animals/river-otter',
            'social_image' => 'https://example.test/images/river-otter.jpg',
            'display_order' => '3',
            'status' => 'published',
            'is_featured' => '1',
            'is_homepage_visible' => '1',
            'new_faqs' => [
                ['question' => 'Where do otters live?', 'answer' => 'They live near clean waterways.'],
            ],
            'related_animals' => [$related->id],
            'new_gallery_images' => [
                [
                    'file' => $galleryImage,
                    'alt_text' => 'Otter portrait',
                    'caption' => 'A river otter portrait.',
                    'credit' => 'Photo team',
                    'source' => 'Archive',
                ],
            ],
        ])->assertRedirect(route('admin.animals.index'));

        $animal = Animal::where('slug', 'river-otter')->firstOrFail();
        $this->assertDatabaseHas('animals', [
            'id' => $animal->id,
            'scientific_name' => 'Lutra lutra',
            'slug' => 'river-otter',
            'short_description' => 'A short otter profile.',
            'kingdom' => 'Animalia',
            'animal_class' => 'Mammalia',
            'animal_order' => 'Carnivora',
            'family' => 'Mustelidae',
            'genus' => 'Lutra',
            'species' => 'Lutra lutra',
            'habitat' => 'Freshwater rivers',
            'geographical_range' => 'Across Europe and Asia.',
            'native_countries' => 'United Kingdom, France',
            'distribution' => 'Coastal and inland waterways.',
            'diet' => 'Fish and invertebrates',
            'lifespan' => '8-12 years',
            'average_weight' => '7-12 kg',
            'average_height_length' => '57-95 cm',
            'speed' => 'Up to 12 km/h',
            'behaviour' => 'Mostly solitary.',
            'reproduction' => 'One litter per year.',
            'ecological_role' => 'Aquatic predator.',
            'conservation_status' => 'Near Threatened',
            'population_information' => 'Population varies by region.',
            'major_threats' => 'Water pollution.',
            'conservation_efforts' => 'River restoration.',
            'image_alt_text' => 'Otter swimming in a river',
            'image_caption' => 'An otter in freshwater.',
            'image_credit' => 'Wildlife Kingdom',
            'image_source' => 'Field archive',
            'meta_title' => 'River Otter Facts',
            'meta_description' => 'Learn about river otters and their habitat.',
            'canonical_url' => 'https://example.test/animals/river-otter',
            'social_image' => 'https://example.test/images/river-otter.jpg',
            'display_order' => 3,
        ]);
        $this->assertSame('Animalia', $animal->kingdom);
        $this->assertSame('A short otter profile.', $animal->short_description);
        $this->assertSame('Near Threatened', $animal->conservation_status);
        $this->assertSame('https://example.test/animals/river-otter', $animal->canonical_url);
        $this->assertTrue($animal->is_featured);
        $this->assertTrue($animal->is_homepage_visible);
        $this->assertSame('Where do otters live?', $animal->faqs()->firstOrFail()->question);
        $this->assertSame('Otter portrait', $animal->images()->firstOrFail()->alt_text);
        $this->assertSame([$related->id], $animal->relatedAnimals()->pluck('animals.id')->all());
        Storage::disk('legacy_uploads')->assertExists('animals/gallery/'.$animal->images()->firstOrFail()->image);

        $editPage = $this->get(route('admin.animals.edit', $animal))
            ->assertOk()
            ->assertSee('River Otter Facts')
            ->assertSee('Where do otters live?')
            ->assertSee('Otter portrait')
            ->assertSee('Animalia');
        foreach ([
            'Mammalia',
            'Carnivora',
            'Mustelidae',
            'Lutra',
            'Across Europe and Asia.',
            'United Kingdom, France',
            'Coastal and inland waterways.',
            'Fish and invertebrates',
            '8-12 years',
            '7-12 kg',
            '57-95 cm',
            'Up to 12 km/h',
            'Mostly solitary.',
            'One litter per year.',
            'Aquatic predator.',
            'Population varies by region.',
            'Water pollution.',
            'River restoration.',
            'Near Threatened',
            'An otter in freshwater.',
            'Wildlife Kingdom',
            'Field archive',
            'Learn about river otters and their habitat.',
            'https://example.test/images/river-otter.jpg',
        ] as $savedValue) {
            $editPage->assertSee($savedValue);
        }

        $faq = $animal->faqs()->firstOrFail();
        $image = $animal->images()->firstOrFail();
        $this->put(route('admin.animals.update', $animal), [
            'name' => 'River Otter',
            'slug' => 'river-otter',
            'short_description' => 'An updated otter profile.',
            'conservation_status' => 'Least Concern',
            'category' => 'Mammals',
            'description' => 'Updated full otter profile.',
            'status' => 'published',
            'kingdom' => 'Animalia updated',
            'meta_title' => 'Updated river otter facts',
            'meta_description' => 'Updated search description.',
            'canonical_url' => 'https://example.test/animals/river-otter',
            'display_order' => '4',
            'is_homepage_visible' => '1',
            'faqs' => [
                $faq->id => ['question' => 'Updated question?', 'answer' => 'Updated answer.'],
            ],
            'images' => [
                $image->id => ['alt_text' => 'Updated otter alt text', 'caption' => 'Updated caption.'],
            ],
            'related_animals' => [$related->id],
        ])->assertRedirect(route('admin.animals.index'));

        $animal->refresh();
        $this->assertSame('Animalia updated', $animal->kingdom);
        $this->assertSame('Updated river otter facts', $animal->meta_title);
        $this->assertSame('Updated question?', $animal->faqs()->firstOrFail()->question);
        $this->assertSame('Updated otter alt text', $animal->images()->firstOrFail()->alt_text);
        $this->get(route('admin.animals.edit', $animal))
            ->assertOk()
            ->assertSee('Animalia updated')
            ->assertSee('Updated question?')
            ->assertSee('Updated otter alt text');
    }

    public function test_public_animal_detail_displays_saved_content_and_seo_without_changing_id_url(): void
    {
        $animal = Animal::create([
            'name' => 'River Otter',
            'slug' => 'river-otter',
            'status' => 'published',
            'description' => 'A complete otter profile.',
            'short_description' => 'A short otter profile.',
            'scientific_name' => 'Lutra lutra',
            'kingdom' => 'Animalia',
            'animal_class' => 'Mammalia',
            'animal_order' => 'Carnivora',
            'family' => 'Mustelidae',
            'genus' => 'Lutra',
            'species' => 'Lutra lutra',
            'habitat' => 'Freshwater rivers',
            'geographical_range' => 'Across Europe and Asia.',
            'native_countries' => 'United Kingdom, France',
            'distribution' => 'Coastal and inland waterways.',
            'diet' => 'Fish',
            'lifespan' => '8-12 years',
            'average_weight' => '7-12 kg',
            'average_height_length' => '57-95 cm',
            'speed' => 'Up to 12 km/h',
            'behaviour' => 'Mostly solitary.',
            'reproduction' => 'One litter per year.',
            'ecological_role' => 'Aquatic predator.',
            'conservation_status' => 'Near Threatened',
            'population_information' => 'Population varies by region.',
            'major_threats' => 'Water pollution.',
            'conservation_efforts' => 'River restoration.',
            'meta_title' => 'River Otter Facts',
            'meta_description' => 'Learn about river otters.',
            'canonical_url' => 'https://example.test/animals/river-otter',
            'noindex' => true,
        ]);
        $animal->faqs()->create([
            'question' => 'Where do otters live?',
            'answer' => 'Near clean waterways.',
        ]);
        $animal->images()->create([
            'image' => 'otter-gallery.webp',
            'alt_text' => 'Otter in water',
            'caption' => 'Swimming otter.',
        ]);

        $this->get(route('animal.show', $animal))
            ->assertOk()
            ->assertSee('A complete otter profile.')
            ->assertSee('Fish')
            ->assertSee('Near Threatened')
            ->assertSee('Mammalia')
            ->assertSee('Across Europe and Asia.')
            ->assertSee('United Kingdom, France')
            ->assertSee('7-12 kg')
            ->assertSee('57-95 cm')
            ->assertSee('Mostly solitary.')
            ->assertSee('One litter per year.')
            ->assertSee('Aquatic predator.')
            ->assertSee('Population varies by region.')
            ->assertSee('Water pollution.')
            ->assertSee('River restoration.')
            ->assertSee('Where do otters live?')
            ->assertSee('alt="Otter in water"', false)
            ->assertSee('<meta name="description" content="Learn about river otters.">', false)
            ->assertSee('<link rel="canonical" href="https://example.test/animals/river-otter">', false)
            ->assertSee('<meta name="robots" content="noindex, follow">', false)
            ->assertSee('"@type":"FAQPage"', false);

        $this->assertStringEndsWith('/animals/'.$animal->id, route('animal.show', $animal));
    }

    public function test_partial_faq_entries_are_rejected_without_creating_animal(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.animals.store'), [
                'name' => 'Invalid Otter',
                'conservation_status' => 'Not assessed',
                'category' => 'Mammals',
                'description' => 'Profile.',
                'status' => 'draft',
                'new_faqs' => [
                    ['question' => 'Where does it live?', 'answer' => ''],
                ],
            ])
            ->assertSessionHasErrors('new_faqs.0.answer');

        $this->assertDatabaseMissing('animals', ['name' => 'Invalid Otter']);
    }
}
