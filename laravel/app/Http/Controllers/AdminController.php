<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Habitat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function loginForm()
    {
        return view('admin.auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        throw ValidationException::withMessages([
            'email' => ['The supplied credentials do not match our records.'],
        ]);
    }

    public function dashboard()
    {
        $stats = [
            'animals' => Animal::count(),
            'habitats' => Habitat::count(),
            'upcoming_events' => Event::where('event_date', '>=', now()->toDateString())->count(),
            'gallery_images' => Gallery::count(),
        ];

        $recentAnimals = Animal::latest()->take(5)->get();
        $recentHabitats = Habitat::latest()->take(5)->get();
        $upcomingEvents = Event::where('event_date', '>=', now()->toDateString())->orderBy('event_date')->take(5)->get();
        $recentGallery = Gallery::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentAnimals',
            'recentHabitats',
            'upcomingEvents',
            'recentGallery'
        ));
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
