<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TunisianDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Tunisian Categories
        $categories = [
            'Informatique & Technologie',
            'Marketing & Communication',
            'Vente & Commerce',
            'Finance & Comptabilité',
            'Ressources Humaines',
            'Ingénierie & Industrie',
            'Santé & Médical',
            'Éducation & Enseignement',
            'Hôtellerie & Restauration',
            'Transport & Logistique'
        ];

        foreach ($categories as $catName) {
            Category::updateOrCreate(
                ['slug' => Str::slug($catName)],
                ['name' => $catName]
            );
        }

        // 2. Create a Tunisian Company User if not exists
        $company = User::updateOrCreate(
            ['email' => 'contact@startup-tunisie.tn'],
            [
                'name' => 'Tech Solutions Tunisie',
                'password' => Hash::make('password'),
                'role' => User::ROLE_COMPANY,
                'sector' => 'Informatique',
                'address' => 'Avenue Habib Bourguiba',
                'city' => 'Tunis',
                'company_size' => '10-50 employés',
                'phone' => '71000000',
                'tax_id' => '1234567/A/P/000',
                'is_validated' => true,
            ]
        );

        // 3. Create Tunisian Job Offers
        $catIT = Category::where('name', 'Informatique & Technologie')->first();
        $catFinance = Category::where('name', 'Finance & Comptabilité')->first();

        $offers = [
            [
                'title' => 'Développeur Fullstack Laravel/Vue',
                'description' => "Nous recherchons un développeur passionné pour rejoindre notre équipe à Tunis. Vous travaillerez sur des projets innovants.",
                'requirements' => "Maîtrise de PHP, Laravel, Vue.js et MySQL.\nExpérience avec les APIs REST.",
                'location' => 'Tunis',
                'type' => 'full-time',
                'category_id' => $catIT->id,
                'education_level' => 'Bac+5',
                'experience_years' => 2,
                'salary' => 2500,
                'expiration_date' => now()->addMonths(1),
                'vacancies' => 2,
                'status' => 'open'
            ],
            [
                'title' => 'Comptable Senior',
                'description' => "Cabinet d'expertise comptable à Sfax cherche un comptable senior avec une solide expérience.",
                'requirements' => "Diplôme en comptabilité.\nMaîtrise des logiciels comptables tunisiens.",
                'location' => 'Sfax',
                'type' => 'full-time',
                'category_id' => $catFinance->id,
                'education_level' => 'Bac+3',
                'experience_years' => 5,
                'salary' => 1800,
                'expiration_date' => now()->addWeeks(2),
                'vacancies' => 1,
                'status' => 'open'
            ],
            [
                'title' => 'Ingénieur Réseaux & Sécurité',
                'description' => "Poste basé à Sousse pour la gestion de l'infrastructure réseau d'une grande industrie.",
                'requirements' => "Certifications Cisco (CCNA/CCNP).\nConnaissance des firewalls Fortinet.",
                'location' => 'Sousse',
                'type' => 'full-time',
                'category_id' => $catIT->id,
                'education_level' => 'Bac+5',
                'experience_years' => 3,
                'salary' => 2200,
                'expiration_date' => now()->addMonths(2),
                'vacancies' => 1,
                'status' => 'open'
            ],
            [
                'title' => 'Conseiller Clientèle Bilingue',
                'description' => "Centre d'appels à l'Ariana cherche des agents bilingues (Français/Anglais).",
                'requirements' => "Excellente élocution.\nMaîtrise des deux langues.",
                'location' => 'Ariana',
                'type' => 'full-time',
                'category_id' => Category::where('name', 'Vente & Commerce')->first()->id,
                'education_level' => 'Bac',
                'experience_years' => 0,
                'salary' => 1200,
                'expiration_date' => now()->addMonths(1),
                'vacancies' => 10,
                'status' => 'open'
            ],
            [
                'title' => 'Chef de Cuisine',
                'description' => "Hôtel 5 étoiles à Hammamet (Nabeul) cherche un chef de cuisine expérimenté.",
                'requirements' => "Expérience en hôtellerie de luxe.\nManagement d'équipe.",
                'location' => 'Nabeul',
                'type' => 'full-time',
                'category_id' => Category::where('name', 'Hôtellerie & Restauration')->first()->id,
                'education_level' => 'Bac+2',
                'experience_years' => 10,
                'salary' => 3500,
                'expiration_date' => now()->addMonths(3),
                'vacancies' => 1,
                'status' => 'open'
            ]
        ];

        foreach ($offers as $offerData) {
            JobOffer::create(array_merge($offerData, ['user_id' => $company->id]));
        }
    }
}
