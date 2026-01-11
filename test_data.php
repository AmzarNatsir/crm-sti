<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Customer;

echo "Total Customers: " . Customer::count() . "\n";
$customers = Customer::limit(5)->get();
foreach ($customers as $c) {
    echo "ID: {$c->id}, Name: {$c->name}, Type: {$c->type}\n";
}
