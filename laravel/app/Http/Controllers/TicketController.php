<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /** Images yahan save hoti hain: laravel/public/uploads/tickets  (storage:link ki zaroorat nahi) */
    private const IMAGE_DIR = 'uploads/tickets';

    public function index()
    {
        $query = Ticket::query();

        if (Schema::hasTable('bookings')) {
            $query->withCount('bookings');
        }

        $tickets = $query->latest()->get();

        return view('admin.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('admin.tickets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $validated['image'] = $this->saveImage($request);
        unset($validated['remove_image']);

        Ticket::create($validated);

        return redirect()->route('admin.tickets.index')->with('success', 'Ticket created successfully.');
    }

    public function edit(Ticket $ticket)
    {
        return view('admin.tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate($this->rules());
        unset($validated['image'], $validated['remove_image']);

        if ($request->hasFile('image')) {
            // nayi image aayi: purani hatao, nayi save karo
            $this->deleteImage($ticket->image);
            $validated['image'] = $this->saveImage($request);
        } elseif ($request->input('remove_image') === '1') {
            // admin ne "Remove" dabaya
            $this->deleteImage($ticket->image);
            $validated['image'] = null;
        }

        $ticket->update($validated);

        return redirect()->route('admin.tickets.index')->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        if (Schema::hasTable('bookings') && $ticket->bookings()->exists()) {
            return redirect()
                ->route('admin.tickets.index')
                ->with('error', 'This ticket type cannot be deleted because bookings depend on it.');
        }

        $this->deleteImage($ticket->image);
        $ticket->delete();

        return redirect()->route('admin.tickets.index')->with('success', 'Ticket deleted successfully.');
    }

    /* ---------------- helpers ---------------- */

    private function rules(): array
    {
        return [
            'ticket_type' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_image' => ['nullable', 'in:0,1'],
        ];
    }

    /** Image ko laravel/public/uploads/tickets me save karke DB wala path (uploads/tickets/xxx.jpg) return karta hai. */
    private function saveImage(Request $request): ?string
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        $file = $request->file('image');
        $dir = public_path(self::IMAGE_DIR);
        File::ensureDirectoryExists($dir);

        $name = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $name);

        return self::IMAGE_DIR . '/' . $name;
    }

    private function deleteImage(?string $path): void
    {
        if (!$path || !Str::startsWith($path, self::IMAGE_DIR . '/')) {
            return; // sirf apne upload folder ki files hi delete hongi
        }

        $full = public_path($path);
        if (File::exists($full)) {
            File::delete($full);
        }
    }
}