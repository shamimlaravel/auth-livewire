<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private const string PASSWORD = 'password';

    public function run(): void
    {
        $this->createAdmin();
        $this->createSeller();
        $this->createReseller();
        $this->createSupportAgent();
        $this->createSampleUsers();
    }

    private function createAdmin(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => bcrypt(self::PASSWORD),
                'email_verified_at' => now(),
                'auth_provider' => 'email',
            ],
        );

        if (! $user->hasRole('admin')) {
            $user->assignRole('admin');
        }

        $this->ensureProfileAndAddress($user, [
            'phone' => '+1-555-0100',
            'company' => 'Auth Inc.',
            'timezone' => 'America/New_York',
            'address_line_1' => '100 Admin Plaza',
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10001',
            'country' => 'US',
        ]);
    }

    private function createSeller(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'seller@example.com'],
            [
                'name' => 'Sarah Seller',
                'username' => 'seller',
                'password' => bcrypt(self::PASSWORD),
                'email_verified_at' => now(),
                'auth_provider' => 'email',
            ],
        );

        if (! $user->hasRole('seller')) {
            $user->assignRole('seller');
        }

        $this->ensureProfileAndAddress($user, [
            'phone' => '+1-555-0200',
            'company' => 'Acme Corp',
            'timezone' => 'America/Chicago',
            'address_line_1' => '200 Market Street',
            'city' => 'Chicago',
            'state' => 'IL',
            'postal_code' => '60601',
            'country' => 'US',
        ]);
    }

    private function createReseller(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'reseller@example.com'],
            [
                'name' => 'Ryan Reseller',
                'username' => 'reseller',
                'password' => bcrypt(self::PASSWORD),
                'email_verified_at' => now(),
                'auth_provider' => 'email',
            ],
        );

        if (! $user->hasRole('reseller')) {
            $user->assignRole('reseller');
        }

        $this->ensureProfileAndAddress($user, [
            'phone' => '+1-555-0300',
            'company' => 'BestDeals Resale',
            'timezone' => 'America/Denver',
            'address_line_1' => '300 Commerce Dr',
            'city' => 'Denver',
            'state' => 'CO',
            'postal_code' => '80201',
            'country' => 'US',
        ]);
    }

    private function createSupportAgent(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'support@example.com'],
            [
                'name' => 'Sam Support',
                'username' => 'support',
                'password' => bcrypt(self::PASSWORD),
                'email_verified_at' => now(),
                'auth_provider' => 'email',
            ],
        );

        if (! $user->hasRole('support_agent')) {
            $user->assignRole('support_agent');
        }

        $this->ensureProfileAndAddress($user, [
            'phone' => '+1-555-0400',
            'company' => 'Auth Inc. Support',
            'timezone' => 'America/Los_Angeles',
            'address_line_1' => '400 Helpdesk Ave',
            'city' => 'Los Angeles',
            'state' => 'CA',
            'postal_code' => '90001',
            'country' => 'US',
        ]);
    }

    private function createSampleUsers(): void
    {
        $sampleUsers = [
            ['name' => 'Alice Johnson', 'username' => 'alice', 'email' => 'alice@example.com'],
            ['name' => 'Bob Williams', 'username' => 'bob', 'email' => 'bob@example.com'],
            ['name' => 'Carol Martinez', 'username' => 'carol', 'email' => 'carol@example.com'],
            ['name' => 'David Brown', 'username' => 'david', 'email' => 'david@example.com'],
            ['name' => 'Eve Davis', 'username' => 'eve', 'email' => 'eve@example.com'],
            ['name' => 'Frank Garcia', 'username' => 'frank', 'email' => 'frank@example.com'],
            ['name' => 'Grace Lee', 'username' => 'grace', 'email' => 'grace@example.com'],
            ['name' => 'Henry Wilson', 'username' => 'henry', 'email' => 'henry@example.com'],
        ];

        $addresses = [
            ['address_line_1' => '500 Oak St', 'city' => 'Seattle', 'state' => 'WA', 'postal_code' => '98101'],
            ['address_line_1' => '600 Maple Ave', 'city' => 'Portland', 'state' => 'OR', 'postal_code' => '97201'],
            ['address_line_1' => '700 Pine Rd', 'city' => 'San Francisco', 'state' => 'CA', 'postal_code' => '94101'],
            ['address_line_1' => '800 Elm St', 'city' => 'Austin', 'state' => 'TX', 'postal_code' => '73301'],
            ['address_line_1' => '900 Birch Ln', 'city' => 'Boston', 'state' => 'MA', 'postal_code' => '02101'],
            ['address_line_1' => '1000 Cedar Blvd', 'city' => 'Miami', 'state' => 'FL', 'postal_code' => '33101'],
            ['address_line_1' => '1100 Walnut Dr', 'city' => 'Atlanta', 'state' => 'GA', 'postal_code' => '30301'],
            ['address_line_1' => '1200 Cherry Ct', 'city' => 'Dallas', 'state' => 'TX', 'postal_code' => '75201'],
        ];

        foreach ($sampleUsers as $i => $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'username' => $data['username'],
                    'password' => bcrypt(self::PASSWORD),
                    'email_verified_at' => now(),
                    'auth_provider' => 'email',
                ],
            );

            if (! $user->hasRole('user')) {
                $user->assignRole('user');
            }

            $addr = $addresses[$i];

            $this->ensureProfileAndAddress($user, [
                'phone' => fake()->unique()->phoneNumber(),
                'company' => fake()->company(),
                'timezone' => fake()->timezone(),
                'address_line_1' => $addr['address_line_1'],
                'city' => $addr['city'],
                'state' => $addr['state'],
                'postal_code' => $addr['postal_code'],
                'country' => 'US',
            ]);
        }
    }

    private function ensureProfileAndAddress(User $user, array $data): void
    {
        $profile = Profile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $data['phone'] ?? null,
                'company' => $data['company'] ?? null,
                'timezone' => $data['timezone'] ?? 'UTC',
                'locale' => 'en',
            ],
        );

        if ($profile->addresses()->where('is_primary', true)->doesntExist()) {
            $profile->addresses()->create([
                'address_line_1' => $data['address_line_1'] ?? fake()->streetAddress(),
                'city' => $data['city'] ?? fake()->city(),
                'state' => $data['state'] ?? fake()->state(),
                'postal_code' => $data['postal_code'] ?? fake()->postcode(),
                'country' => $data['country'] ?? 'US',
                'is_primary' => true,
            ]);
        }
    }
}
