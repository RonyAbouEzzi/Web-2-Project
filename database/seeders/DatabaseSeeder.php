<?php

namespace Database\Seeders;

use App\Models\{Municipality, Office, Service, ServiceCategory, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ─────────────────────────────────────────────────
        User::create([
            'name'              => 'System Admin',
            'email'             => 'admin@eservices.gov',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        // ── Municipalities ────────────────────────────────────────
        $municipalities = [
            ['name' => 'Beirut', 'region' => 'Beirut Governorate'],
            ['name' => 'Baabda', 'region' => 'Mount Lebanon'],
            ['name' => 'Tripoli', 'region' => 'North Governorate'],
        ];

        foreach ($municipalities as $m) {
            $municipality = Municipality::create(array_merge($m, ['country' => 'Lebanon']));

            // ── Office ────────────────────────────────────────────
            $office = Office::create([
                'municipality_id' => $municipality->id,
                'name'            => $m['name'] . ' Municipal Office',
                'address'         => 'Main Street, ' . $m['name'],
                'latitude'        => 33.8938 + rand(-5, 5) / 100,
                'longitude'       => 35.5018 + rand(-5, 5) / 100,
                'phone'           => '+961 1 ' . rand(100000, 999999),
                'email'           => 'office@' . strtolower($m['name']) . '.gov.lb',
                'working_hours'   => [
                    'mon' => '08:00-16:00', 'tue' => '08:00-16:00',
                    'wed' => '08:00-16:00', 'thu' => '08:00-16:00',
                    'fri' => '08:00-14:00', 'sat' => 'closed', 'sun' => 'closed',
                ],
            ]);

            // ── Office User ───────────────────────────────────────
            $officeUser = User::create([
                'name'              => $m['name'] . ' Office Manager',
                'email'             => 'manager@' . strtolower($m['name']) . '.gov.lb',
                'password'          => Hash::make('password'),
                'role'              => 'office_user',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]);
            $officeUser->offices()->attach($office->id, ['role' => 'manager']);

            // ── Categories & Services ─────────────────────────────
            $categoriesData = [
                ['name' => 'Civil Registry', 'icon' => 'bi-person-vcard', 'services' => [
                    ['name' => 'Birth Certificate', 'price' => 15.00, 'duration' => 3, 'docs' => ['Hospital record', 'Parent ID copies']],
                    ['name' => 'Marriage Certificate', 'price' => 25.00, 'duration' => 5, 'docs' => ['ID copies', 'Religious record']],
                    ['name' => 'Death Certificate', 'price' => 10.00, 'duration' => 2, 'docs' => ['Hospital record', 'Family register']],
                ]],
                ['name' => 'Real Estate', 'icon' => 'bi-house', 'services' => [
                    ['name' => 'Property Ownership Transfer', 'price' => 150.00, 'duration' => 10, 'docs' => ['Title deed', 'ID copy', 'Tax clearance']],
                    ['name' => 'Building Permit', 'price' => 200.00, 'duration' => 15, 'docs' => ['Land deed', 'Engineering plans', 'ID copy']],
                ]],
                ['name' => 'Licenses', 'icon' => 'bi-card-checklist', 'services' => [
                    ['name' => 'Business License', 'price' => 80.00, 'duration' => 7, 'docs' => ['Trade registration', 'Lease agreement', 'ID copy']],
                    ['name' => 'Vehicle Registration', 'price' => 50.00, 'duration' => 3, 'docs' => ['Car documents', 'Insurance', 'ID copy']],
                ]],
            ];

            foreach ($categoriesData as $catData) {
                $category = ServiceCategory::create([
                    'office_id'   => $office->id,
                    'name'        => $catData['name'],
                    'icon'        => $catData['icon'],
                ]);

                foreach ($catData['services'] as $svc) {
                    Service::create([
                        'office_id'               => $office->id,
                        'category_id'             => $category->id,
                        'name'                    => $svc['name'],
                        'price'                   => $svc['price'],
                        'estimated_duration_days' => $svc['duration'],
                        'required_documents'      => $svc['docs'],
                        'is_active'               => true,
                    ]);
                }
            }
        }

        // ── Sample Citizens ───────────────────────────────────────
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name'              => "Test Citizen {$i}",
                'email'             => "citizen{$i}@test.com",
                'password'          => Hash::make('password'),
                'role'              => 'citizen',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]);
        }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin',       'admin@eservices.gov',          'password'],
                ['Office User', 'manager@beirut.gov.lb',        'password'],
                ['Citizen',     'citizen1@test.com',            'password'],
            ]
        );
    }
}
