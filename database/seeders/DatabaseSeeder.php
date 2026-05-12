<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@5thpillar.local');
        $password = env('ADMIN_PASSWORD', 'changeme');

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => 'CMS Admin',
                'password' => Hash::make($password),
                'is_admin' => true,
            ],
        );

        $this->call(NavMenuSeeder::class);
    }
}
