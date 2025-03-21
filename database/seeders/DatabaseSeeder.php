<?php

namespace Database\Seeders;

use App\Models\CarListing;
use App\Models\Feature;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);

        // Create regular users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create car features
        $safetyFeatures = [
            'ABS Brakes',
            'Airbags',
            'Electronic Stability Control',
            'Traction Control',
            'Lane Departure Warning',
            'Blind Spot Monitoring',
            'Reversing Camera',
            'Parking Sensors',
        ];

        $comfortFeatures = [
            'Air Conditioning',
            'Leather Seats',
            'Heated Seats',
            'Sunroof',
            'Power Windows',
            'Power Seats',
            'Cruise Control',
            'Climate Control',
        ];

        $technologyFeatures = [
            'Bluetooth Connectivity',
            'Navigation System',
            'USB Ports',
            'Apple CarPlay',
            'Android Auto',
            'Wireless Charging',
            'Premium Sound System',
            'Digital Dashboard',
        ];

        foreach ($safetyFeatures as $feature) {
            Feature::create([
                'name' => $feature,
                'category' => 'Safety',
            ]);
        }

        foreach ($comfortFeatures as $feature) {
            Feature::create([
                'name' => $feature,
                'category' => 'Comfort',
            ]);
        }

        foreach ($technologyFeatures as $feature) {
            Feature::create([
                'name' => $feature,
                'category' => 'Technology',
            ]);
        }

        // Create car listings
        $carData = [
            [
                'title' => '2019 Toyota Corolla Ascent Sport',
                'description' => 'Well-maintained Toyota Corolla Ascent Sport with low mileage. Perfect for city driving and great fuel economy.',
                'price' => 21990,
                'year' => 2019,
                'make' => 'Toyota',
                'model' => 'Corolla',
                'trim' => 'Ascent Sport',
                'body_type' => 'Hatchback',
                'fuel_type' => 'Petrol',
                'transmission' => 'Automatic',
                'odometer' => 45000,
                'color' => 'Silver',
                'user_id' => 2,
                'featured' => true,
            ],
            [
                'title' => '2018 Mazda CX-5 Touring AWD',
                'description' => 'Sporty and spacious Mazda CX-5 Touring with all-wheel drive. Perfect for families and outdoor adventures.',
                'price' => 29990,
                'year' => 2018,
                'make' => 'Mazda',
                'model' => 'CX-5',
                'trim' => 'Touring',
                'body_type' => 'SUV',
                'fuel_type' => 'Petrol',
                'transmission' => 'Automatic',
                'odometer' => 60000,
                'color' => 'Red',
                'user_id' => 2,
            ],
            [
                'title' => '2020 Tesla Model 3 Standard Range Plus',
                'description' => 'Like new Tesla Model 3 with Autopilot. Zero emissions and amazing technology.',
                'price' => 59990,
                'year' => 2020,
                'make' => 'Tesla',
                'model' => 'Model 3',
                'trim' => 'Standard Range Plus',
                'body_type' => 'Sedan',
                'fuel_type' => 'Electric',
                'transmission' => 'Automatic',
                'odometer' => 20000,
                'color' => 'White',
                'user_id' => 3,
                'featured' => true,
            ],
            [
                'title' => '2017 Ford Ranger XLT 4x4',
                'description' => 'Tough and reliable Ford Ranger with 4x4 capability. Perfect for work and play.',
                'price' => 35990,
                'year' => 2017,
                'make' => 'Ford',
                'model' => 'Ranger',
                'trim' => 'XLT',
                'body_type' => 'Ute',
                'fuel_type' => 'Diesel',
                'transmission' => 'Automatic',
                'odometer' => 85000,
                'color' => 'Blue',
                'user_id' => 3,
            ],
            [
                'title' => '2021 Hyundai i30 N Performance',
                'description' => 'Hot hatch with sports performance. This i30 N is a thrill to drive with its turbocharged engine.',
                'price' => 42990,
                'year' => 2021,
                'make' => 'Hyundai',
                'model' => 'i30',
                'trim' => 'N Performance',
                'body_type' => 'Hatchback',
                'fuel_type' => 'Petrol',
                'transmission' => 'Manual',
                'odometer' => 15000,
                'color' => 'Blue',
                'user_id' => 2,
                'featured' => true,
            ],
        ];

        foreach ($carData as $car) {
            $listing = CarListing::create($car);

            // Attach random features to each listing
            $features = Feature::inRandomOrder()->limit(rand(5, 10))->get();
            $listing->features()->attach($features->pluck('id')->toArray());
        }
    }
}