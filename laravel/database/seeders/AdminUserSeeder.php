<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\Enquiry;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Habitat;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@wildlifekingdom.com'],
            [
                'name' => 'Wildlife Kingdom Admin',
                'password' => Hash::make('password'),
            ]
        );

        if (Habitat::count() === 0) {
            $habitats = [
                ['name' => 'Savannah Plains', 'short_description' => 'Wide open grasslands', 'description' => 'The savannah is an expansive grassland where large herbivores roam across the open fields.', 'status' => 'published'],
                ['name' => 'Rainforest Canopy', 'short_description' => 'Lush and dense', 'description' => 'Rich tropical foliage creates a layered habitat filled with birds, primates, and hidden wildlife.', 'status' => 'published'],
                ['name' => 'Riverbank', 'short_description' => 'Waterfront ecosystem', 'description' => 'A lively water edge where amphibians, fish, and birds gather in harmony.', 'status' => 'published'],
            ];

            foreach ($habitats as $habitat) {
                Habitat::create($habitat);
            }
        }

        if (Animal::count() === 0) {
            $habitatIds = Habitat::pluck('id')->all();

            Animal::create([
                'name' => 'African Lion',
                'scientific_name' => 'Panthera leo',
                'category' => 'Mammals',
                'description' => 'The king of the savannah is known for its power, teamwork, and iconic roar.',
                'habitat_id' => $habitatIds[0] ?? null,
                'status' => 'published',
            ]);

            Animal::create([
                'name' => 'Giraffe',
                'scientific_name' => 'Giraffa camelopardalis',
                'category' => 'Mammals',
                'description' => 'Giraffes move gracefully across the grasslands, browsing leaves from the tallest trees.',
                'habitat_id' => $habitatIds[0] ?? null,
                'status' => 'published',
            ]);

            Animal::create([
                'name' => 'Harpy Eagle',
                'scientific_name' => 'Harpia harpyja',
                'category' => 'Birds',
                'description' => 'This powerful bird of prey can watch the rainforest canopy below with remarkable focus.',
                'habitat_id' => $habitatIds[1] ?? null,
                'status' => 'published',
            ]);
        }

        if (Event::count() === 0) {
            Event::create([
                'title' => 'Sunrise Safari Walk',
                'description' => 'Enjoy a guided morning walk and witness the animals waking to the first light.',
                'location' => 'Savannah Plains',
                'event_date' => now()->addDays(3)->toDateString(),
                'event_time' => '08:00 AM',
                'status' => 'published',
            ]);

            Event::create([
                'title' => 'Conservation Talk',
                'description' => 'Meet our conservation team and learn how wildlife protection programs make a difference.',
                'location' => 'Education Centre',
                'event_date' => now()->addDays(10)->toDateString(),
                'event_time' => '11:30 AM',
                'status' => 'published',
            ]);
        }

        if (Gallery::count() === 0) {
            Gallery::create([
                'title' => 'Lion Pride',
                'description' => 'A close view of the pride resting in the grasslands.',
                'category' => 'big-cats',
                'status' => 'published',
                'image' => 'gallery/lion-pride.jpg',
            ]);

            Gallery::create([
                'title' => 'Riverbank Drift',
                'description' => 'Birds and wildlife gathering along the waterline.',
                'category' => 'habitats',
                'status' => 'published',
                'image' => 'gallery/riverbank-drift.jpg',
            ]);
        }

        if (Ticket::count() === 0) {
            Ticket::create(['ticket_type' => 'Adult', 'price' => 24.00, 'description' => 'Entry for adult visitors', 'status' => 'active']);
            Ticket::create(['ticket_type' => 'Child', 'price' => 16.00, 'description' => 'Entry for children aged 3-12', 'status' => 'active']);
            Ticket::create(['ticket_type' => 'Family Pass', 'price' => 60.00, 'description' => 'Two adults and up to three children', 'status' => 'active']);
        }

        if (Enquiry::count() === 0) {
            Enquiry::create([
                'name' => 'Test Visitor',
                'email' => 'visitor@example.com',
                'phone' => '9876543210',
                'subject' => 'School Trip',
                'message' => 'We would like to plan a school visit for next month.',
                'status' => 'new',
            ]);
        }
    }
}
