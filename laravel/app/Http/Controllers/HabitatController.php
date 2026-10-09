<?php

namespace App\Http\Controllers;

use App\Models\Habitat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HabitatController extends Controller
{
    public function index()
    {
        $habitats = Habitat::latest()->get();

        return view('admin.habitats.index', compact('habitats'));
    }

    public function create()
    {
        return view('admin.habitats.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
            'status' => ['required', 'in:published,draft'],
        ]);

        $validated['image'] = $this->handleImageUpload($request, null);

        Habitat::create($validated);

        return redirect()->route('admin.habitats.index')->with('success', 'Habitat created successfully.');
    }

    public function edit(Habitat $habitat)
    {
        return view('admin.habitats.edit', compact('habitat'));
    }

    public function update(Request $request, Habitat $habitat)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
            'status' => ['required', 'in:published,draft'],
        ]);

        $validated['image'] = $this->handleImageUpload($request, $habitat->image);

        $habitat->update($validated);

        return redirect()->route('admin.habitats.index')->with('success', 'Habitat updated successfully.');
    }

    public function destroy(Habitat $habitat)
    {
        if ($habitat->image) {
            Storage::disk('legacy_uploads')->delete('habitats/'.basename($habitat->image));
        }

        $habitat->delete();

        return redirect()->route('admin.habitats.index')->with('success', 'Habitat deleted successfully.');
    }

    protected function handleImageUpload(Request $request, ?string $currentImage = null): ?string
    {
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('habitats', 'legacy_uploads');

            if ($currentImage) {
                Storage::disk('legacy_uploads')->delete('habitats/'.basename($currentImage));
            }

            return basename($path);
        }

        return $currentImage;
    }
}
