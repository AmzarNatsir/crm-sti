<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Customer;

$q = "Mittie";
echo "Searching for '$q'...\n";
$data = Customer::where('name', 'LIKE', "%$q%")
        ->select('id', 'name')
        ->limit(20)
        ->get();

foreach ($data as $item) {
    echo "ID: {$item->id}, Name: {$item->name}\n";
}
