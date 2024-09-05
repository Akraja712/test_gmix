<?php

// Load Laravel's autoloader
require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Trips;

// Define the available trip types
$tripTypes = ['Road Trip', 'Adventure Trip', 'Explore Cities', 'Airport Flyover'];

$data = [
    'location' => 'chennai',
    'from_date' => '2024-07-30',
    'to_date' => '2024-08-10',
    'trip_title' => 'Summer Trip',
    'trip_description' => 'Enjoying summer vacation',
    'user_id' => 1, // Replace with the actual user ID
    'trip_datetime' => now(),
    'trip_status' => 1,
    'trip_image' => 'SnXxFLBKoQslEfdyXjAzVvfoHHtT61C6bcJNnXhb.jpg',
];

$numberOfInserts = 10;

for ($i = 0; $i < $numberOfInserts; $i++) {
    // Randomly select a trip type
    $data['trip_type'] = $tripTypes[array_rand($tripTypes)];
    Trips::create($data);
}

echo "Inserted ".$numberOfInserts." records into trips table.\n";
