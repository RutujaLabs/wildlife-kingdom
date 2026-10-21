<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalFaq;
use App\Models\AnimalImage;
use App\Models\Habitat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AnimalController extends Controller
{
    public function index()
    {
        $animals = Animal::with('habitat')->latest()->get();

        return view('admin.animals.index', compact('animals'));
    }

    public function create()
    {
        $habitats = Habitat::orderBy('name')->get();
        $allAnimals = Animal::orderBy('name')->get();
        $animal = null;

        return view('admin.animals.create', compact('habitats', 'allAnimals', 'animal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());
        $newFaqs = $validated['new_faqs'] ?? [];
        $newGalleryMetadata = $validated['new_gallery_images'] ?? [];
        $relatedAnimalIds = $validated['related_animals'] ?? [];
        unset($validated['new_faqs'], $validated['new_gallery_images'], $validated['related_animals']);

        $validated['image'] = $this->handleImageUpload($request, null);
        $validated['slug'] = $this->resolveSlug($validated['slug'] ?? null, $validated['name']);
        $this->setCheckboxValues($validated, $request);

        DB::transaction(function () use ($validated, $newFaqs, $newGalleryMetadata, $relatedAnimalIds, $request) {
            $animal = Animal::create($validated);
            $this->syncFaqs($animal, [], $newFaqs);
            $this->syncImages($animal, [], $request->file('new_gallery_images', []), $newGalleryMetadata);
            $animal->relatedAnimals()->sync(collect($relatedAnimalIds)
                ->map(fn ($id) => (int) $id)
                ->reject(fn ($id) => $id === $animal->id)
                ->unique()
                ->values()
                ->all());
        });

        return redirect()->route('admin.animals.index')->with('success', 'Animal created successfully.');
    }

    public function edit(Animal $animal)
    {
        $habitats = Habitat::orderBy('name')->get();
        $allAnimals = Animal::orderBy('name')->get();

        return view('admin.animals.edit', compact('animal', 'habitats', 'allAnimals'));
    }

    public function preview(Animal $animal)
    {
        $animal->load([
            'habitat',
            'images',
            'faqs',
            'relatedAnimals' => fn ($query) => $query->where('status', 'published')->orderBy('name'),
        ]);

        return view('front.animals.show', [
            'animal' => $animal,
            'preview' => true,
        ]);
    }

    public function update(Request $request, Animal $animal)
    {
        $validated = $request->validate($this->validationRules($animal));
        // Preserve existing values when optional fields are submitted as null
$optionalFields = [
    'habitat',
    'species',
    'diet',
    'lifespan',
];

foreach ($optionalFields as $field) {
    if (array_key_exists($field, $validated) && $validated[$field] === null) {
        $validated[$field] = $animal->{$field};
    }
}
        $existingFaqs = $validated['faqs'] ?? [];
        $newFaqs = $validated['new_faqs'] ?? [];
        $existingImages = $validated['images'] ?? [];
        $newGalleryMetadata = $validated['new_gallery_images'] ?? [];
        $relatedAnimalIds = $validated['related_animals'] ?? [];
        unset($validated['faqs'], $validated['new_faqs'], $validated['images'], $validated['new_gallery_images'], $validated['related_animals']);

        $validated['image'] = $this->handleImageUpload($request, $animal->image);
        $validated['slug'] = $this->resolveSlug($validated['slug'] ?? null, $validated['name'], $animal);
        $this->setCheckboxValues($validated, $request);

        $filesToDelete = DB::transaction(function () use ($animal, $validated, $existingFaqs, $newFaqs, $existingImages, $newGalleryMetadata, $relatedAnimalIds, $request) {
            $animal->update($validated);
            $this->syncFaqs($animal, $existingFaqs, $newFaqs);
            $filesToDelete = $this->syncImages(
                $animal,
                $existingImages,
                $request->file('new_gallery_images', []),
                $newGalleryMetadata
            );
            $animal->relatedAnimals()->sync(collect($relatedAnimalIds)
                ->map(fn ($id) => (int) $id)
                ->reject(fn ($id) => $id === $animal->id)
                ->unique()
                ->values()
                ->all());

            return $filesToDelete;
        });

        foreach ($filesToDelete as $path) {
            Storage::disk('legacy_uploads')->delete($path);
        }

        return redirect()->route('admin.animals.index')->with('success', 'Animal updated successfully.');
    }

    public function destroy(Animal $animal)
    {
        $image = $animal->image;
        $galleryImages = $animal->images()->get();
        $animal->delete();

        if ($image) {
            Storage::disk('legacy_uploads')->delete('animals/'.basename($image));
        }

        foreach ($galleryImages as $galleryImage) {
            Storage::disk('legacy_uploads')->delete('animals/gallery/'.basename($galleryImage->image));
        }

        return redirect()->route('admin.animals.index')->with('success', 'Animal deleted successfully.');
    }

    protected function validationRules(?Animal $animal = null): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'scientific_name' => ['nullable', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:180', 'alpha_dash', Rule::unique('animals', 'slug')->ignore($animal)],
            'short_description' => ['nullable', 'string', 'max:500'],
            'conservation_status' => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:80'],
            'habitat_id' => ['nullable', 'exists:habitats,id'],
            'habitat' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
            'status' => ['required', 'in:published,draft'],
            'is_featured' => ['sometimes', 'boolean'],
            'kingdom' => ['nullable', 'string', 'max:100'],
            'animal_class' => ['nullable', 'string', 'max:100'],
            'animal_order' => ['nullable', 'string', 'max:100'],
            'family' => ['nullable', 'string', 'max:100'],
            'genus' => ['nullable', 'string', 'max:100'],
            'species' => ['nullable', 'string', 'max:150'],
            'geographical_range' => ['nullable', 'string', 'max:10000'],
            'native_countries' => ['nullable', 'string', 'max:5000'],
            'distribution' => ['nullable', 'string', 'max:10000'],
            'diet' => ['nullable', 'string', 'max:255'],
            'lifespan' => ['nullable', 'string', 'max:150'],
            'average_weight' => ['nullable', 'string', 'max:150'],
            'average_height_length' => ['nullable', 'string', 'max:150'],
            'speed' => ['nullable', 'string', 'max:150'],
            'behaviour' => ['nullable', 'string', 'max:10000'],
            'reproduction' => ['nullable', 'string', 'max:10000'],
            'ecological_role' => ['nullable', 'string', 'max:10000'],
            'population_information' => ['nullable', 'string', 'max:10000'],
            'major_threats' => ['nullable', 'string', 'max:10000'],
            'conservation_efforts' => ['nullable', 'string', 'max:10000'],
            'fun_fact' => ['nullable', 'string', 'max:5000'],
            'image_alt_text' => ['nullable', 'string', 'max:255'],
            'image_caption' => ['nullable', 'string', 'max:2000'],
            'image_credit' => ['nullable', 'string', 'max:255'],
            'image_source' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:320'],
            'canonical_url' => ['nullable', 'url', 'max:2048'],
            'social_image' => ['nullable', 'url', 'max:2048'],
            'noindex' => ['sometimes', 'boolean'],
            'nofollow' => ['sometimes', 'boolean'],
            'is_homepage_visible' => ['sometimes', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:2147483647'],
            'related_animals' => ['sometimes', 'array'],
            'related_animals.*' => ['integer', 'exists:animals,id'],
            'faqs' => ['sometimes', 'array'],
            'faqs.*.question' => ['nullable', 'string', 'max:255', 'required_with:faqs.*.answer'],
            'faqs.*.answer' => ['nullable', 'string', 'max:5000', 'required_with:faqs.*.question'],
            'faqs.*.remove' => ['sometimes', 'boolean'],
            'new_faqs' => ['sometimes', 'array', 'max:6'],
            'new_faqs.*.question' => ['nullable', 'string', 'max:255', 'required_with:new_faqs.*.answer'],
            'new_faqs.*.answer' => ['nullable', 'string', 'max:5000', 'required_with:new_faqs.*.question'],
            'new_gallery_images' => ['sometimes', 'array', 'max:6'],
            'new_gallery_images.*.file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
            'new_gallery_images.*.alt_text' => ['nullable', 'string', 'max:255'],
            'new_gallery_images.*.caption' => ['nullable', 'string', 'max:2000'],
            'new_gallery_images.*.credit' => ['nullable', 'string', 'max:255'],
            'new_gallery_images.*.source' => ['nullable', 'string', 'max:255'],
            'images' => ['sometimes', 'array'],
            'images.*.alt_text' => ['nullable', 'string', 'max:255'],
            'images.*.caption' => ['nullable', 'string', 'max:2000'],
            'images.*.credit' => ['nullable', 'string', 'max:255'],
            'images.*.source' => ['nullable', 'string', 'max:255'],
            'images.*.remove' => ['sometimes', 'boolean'],
        ];
    }

    protected function setCheckboxValues(array &$validated, Request $request): void
    {
        foreach (['is_featured', 'is_homepage_visible', 'noindex', 'nofollow'] as $field) {
            $validated[$field] = $request->boolean($field, $field === 'is_homepage_visible');
        }
    }

    protected function resolveSlug(?string $slug, string $name, ?Animal $animal = null): string
    {
        if ($animal && blank($slug) && filled($animal->slug)) {
            return $animal->slug;
        }

        $base = Str::slug($slug ?: $name) ?: 'animal';
        $candidate = $base;
        $suffix = 2;

        while (Animal::query()
            ->where('slug', $candidate)
            ->when($animal, fn ($query) => $query->whereKeyNot($animal->getKey()))
            ->exists()) {
            $candidate = $base.'-'.$suffix++;
        }

        return $candidate;
    }

    protected function syncFaqs(Animal $animal, array $existingFaqs, array $newFaqs): void
    {
        foreach ($existingFaqs as $id => $data) {
            $faq = $animal->faqs()->findOrFail($id);

            if (! empty($data['remove'])) {
                $faq->delete();
                continue;
            }

            if (filled($data['question'] ?? null) && filled($data['answer'] ?? null)) {
                $faq->update([
                    'question' => $data['question'],
                    'answer' => $data['answer'],
                ]);
            }
        }

        foreach ($newFaqs as $data) {
            if (filled($data['question'] ?? null) && filled($data['answer'] ?? null)) {
                $animal->faqs()->create([
                    'question' => $data['question'],
                    'answer' => $data['answer'],
                    'display_order' => $animal->faqs()->count(),
                ]);
            }
        }
    }

    protected function syncImages(Animal $animal, array $existingImages, array $newFiles, array $newMetadata): array
    {
        $filesToDelete = [];

        foreach ($existingImages as $id => $data) {
            $image = $animal->images()->findOrFail($id);

            if (! empty($data['remove'])) {
                $filesToDelete[] = 'animals/gallery/'.basename($image->image);
                $image->delete();
                continue;
            }

            $image->update([
                'alt_text' => $data['alt_text'] ?? null,
                'caption' => $data['caption'] ?? null,
                'credit' => $data['credit'] ?? null,
                'source' => $data['source'] ?? null,
            ]);
        }

        foreach ($newFiles as $index => $data) {
            if (! ($data['file'] ?? null)) {
                continue;
            }

            $path = $data['file']->store('animals/gallery', 'legacy_uploads');
            $metadata = $newMetadata[$index] ?? [];
            $animal->images()->create([
                'image' => basename($path),
                'alt_text' => $metadata['alt_text'] ?? null,
                'caption' => $metadata['caption'] ?? null,
                'credit' => $metadata['credit'] ?? null,
                'source' => $metadata['source'] ?? null,
                'display_order' => $animal->images()->count(),
            ]);
        }

        return $filesToDelete;
    }

    protected function handleImageUpload(Request $request, ?string $currentImage = null): ?string
    {
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('animals', 'legacy_uploads');

            if ($currentImage) {
                Storage::disk('legacy_uploads')->delete('animals/'.basename($currentImage));
            }

            return basename($path);
        }

        return $currentImage;
    }
}
