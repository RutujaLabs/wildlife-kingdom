<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Habitat;
use Illuminate\Support\Facades\Schema;

class FrontendController extends Controller
{
    public function home()
    {
        $featuredAnimals = Animal::query()
            ->when(Schema::hasColumn('animals', 'status'), function ($query) {
                $query->where('status', 'published');
            })
            ->when(Schema::hasColumn('animals', 'is_homepage_visible'), function ($query) {
                $query->where('is_homepage_visible', true);
            })
            ->orderByDesc('is_featured')
            ->orderBy('display_order')
            ->orderBy('name')
            ->limit(4)
            ->get();

        $featuredHabitats = Habitat::query()
            ->when(Schema::hasColumn('habitats', 'status'), function ($query) {
                $query->where('status', 'published');
            })
            ->when(Schema::hasColumn('habitats', 'is_homepage_visible'), function ($query) {
                $query->where('is_homepage_visible', true);
            })
            ->orderBy('display_order')
            ->orderBy('name')
            ->limit(3)
            ->get();

        $upcomingEvents = Event::query()
            ->when(Schema::hasColumn('events', 'status'), function ($query) {
                $query->where('status', 'published');
            })
            ->when(Schema::hasColumn('events', 'is_homepage_visible'), function ($query) {
                $query->where('is_homepage_visible', true);
            })
            ->where('event_date', '>=', now()->toDateString())
            ->orderByDesc('is_featured')
            ->orderBy('display_order')
            ->orderBy('event_date')
            ->limit(2)
            ->get();

        $gallery = Gallery::query()
            ->when(Schema::hasColumn('galleries', 'status'), function ($query) {
                $query->where('status', 'published');
            })
            ->when(Schema::hasColumn('galleries', 'is_homepage_visible'), function ($query) {
                $query->where('is_homepage_visible', true);
            })
            ->orderBy('display_order')
            ->latest()
            ->limit(4)
            ->get();

        return view('front.home', compact('featuredAnimals', 'featuredHabitats', 'upcomingEvents', 'gallery'));
    }

    public function animals()
    {
        $animals = Animal::query()
            ->when(Schema::hasColumn('animals', 'status'), function ($query) {
                $query->where('status', 'published');
            })
            ->orderBy('name')
            ->get();

        return view('front.animals.index', compact('animals'));
    }

    public function animalDetails(Animal $animal)
    {
        if (Schema::hasColumn('animals', 'status') && $animal->status !== 'published') {
            abort(404);
        }

        $animal->load([
            'habitat',
            'images',
            'faqs',
            'relatedAnimals' => fn ($query) => $query->where('status', 'published')->orderBy('name'),
        ]);

        return view('front.animals.show', compact('animal'));
    }

    public function habitats()
    {
        $habitats = Habitat::query()
            ->when(Schema::hasColumn('habitats', 'status'), function ($query) {
                $query->where('status', 'published');
            })
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('front.habitats.index', compact('habitats'));
    }

    public function events()
    {
        $events = Event::query()
            ->when(Schema::hasColumn('events', 'status'), function ($query) {
                $query->where('status', 'published');
            })
            ->orderBy('display_order')
            ->orderBy('event_date', 'asc')
            ->get();

        return view('front.events.index', compact('events'));
    }

    public function gallery()
    {
        $gallery = Gallery::query()
            ->when(Schema::hasColumn('galleries', 'status'), function ($query) {
                $query->where('status', 'published');
            })
            ->orderBy('display_order')
            ->latest()
            ->get();

        return view('front.gallery.index', compact('gallery'));
    }
}
