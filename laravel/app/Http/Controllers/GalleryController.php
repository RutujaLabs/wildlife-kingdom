<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $gallery = Gallery::latest()->get();

        return view('admin.gallery.index', compact('gallery'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string', 'max:80'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
            'status' => ['required', 'in:published,draft'],
        ]);

        $validated['image'] = $this->handleImageUpload($request);

        Gallery::create($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery image created successfully.');
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.gallery.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string', 'max:80'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
            'status' => ['required', 'in:published,draft'],
        ]);

        $validated['image'] = $this->handleImageUpload($request, $gallery->image);

        $gallery->update($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery image updated successfully.');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->image) {
            Storage::disk('legacy_uploads')->delete('gallery/'.basename($gallery->image));
        }

        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery image deleted successfully.');
    }

    protected function handleImageUpload(Request $request, ?string $currentImage = null): ?string
    {
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('gallery', 'legacy_uploads');

            if ($currentImage) {
                Storage::disk('legacy_uploads')->delete('gallery/'.basename($currentImage));
            }

            return basename($path);
        }

        return $currentImage;
    }
}
