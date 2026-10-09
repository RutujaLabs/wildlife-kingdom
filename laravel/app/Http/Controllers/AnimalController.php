<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Habitat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        return view('admin.animals.create', compact('habitats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'scientific_name' => ['nullable', 'string', 'max:180'],
            'conservation_status' => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:80'],
            'habitat_id' => ['nullable', 'exists:habitats,id'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
            'status' => ['required', 'in:published,draft'],
            'is_featured' => ['sometimes', 'boolean'],
        ]);

        $validated['image'] = $this->handleImageUpload($request, null);
        $validated['is_featured'] = $request->boolean('is_featured');

        Animal::create($validated);

        return redirect()->route('admin.animals.index')->with('success', 'Animal created successfully.');
    }

    public function edit(Animal $animal)
    {
        $habitats = Habitat::orderBy('name')->get();

        return view('admin.animals.edit', compact('animal', 'habitats'));
    }

    public function update(Request $request, Animal $animal)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'scientific_name' => ['nullable', 'string', 'max:180'],
            'conservation_status' => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:80'],
            'habitat_id' => ['nullable', 'exists:habitats,id'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
            'status' => ['required', 'in:published,draft'],
            'is_featured' => ['sometimes', 'boolean'],
        ]);

        $validated['image'] = $this->handleImageUpload($request, $animal->image);
        $validated['is_featured'] = $request->boolean('is_featured');

        $animal->update($validated);

        return redirect()->route('admin.animals.index')->with('success', 'Animal updated successfully.');
    }

    public function destroy(Animal $animal)
    {
        $image = $animal->image;
        $animal->delete();

        if ($image) {
            Storage::disk('legacy_uploads')->delete('animals/'.basename($image));
        }

        return redirect()->route('admin.animals.index')->with('success', 'Animal deleted successfully.');
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
