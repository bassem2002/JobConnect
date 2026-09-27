<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\JobOffer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(TunisianDataSeeder::class);
        
        // Créer une entreprise test supplémentaire
        $company = User::updateOrCreate(
            ['email' => 'company@example.com'],
            [
                'name' => 'Tech Corp',
                'password' => Hash::make('password'),
                'role' => 'company',
                'phone' => '0123456789',
                'bio' => 'Nous sommes une entreprise leader dans le domaine de la technologie.',
                'website' => 'https://techcorp.com',
            ]
        );

        // Créer un candidat test supplémentaire
        User::updateOrCreate(
            ['email' => 'candidate@example.com'],
            [
                'name' => 'Jean Candidat',
                'password' => Hash::make('password'),
                'role' => 'candidate',
                'phone' => '0987654321',
                'bio' => 'Développeur Fullstack passionné par Laravel et Vue.js.',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '0111111111',
                'bio' => 'Administrateur du système.',
            ]
        );
    }
}
