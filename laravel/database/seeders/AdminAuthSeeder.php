<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminAuthSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('auth.admin_setup.email');
        $password = config('auth.admin_setup.password');

        if (! is_string($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false
            || ! is_string($password) || $password === '') {
            throw new RuntimeException('Set a valid ADMIN_EMAIL and a non-empty ADMIN_PASSWORD before seeding the admin user.');
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => config('auth.admin_setup.name', 'Administrator'),
                'password' => Hash::make($password),
            ]
        );
    }
}
