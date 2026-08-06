<?php

use Illuminate\Contracts\Console\Kernel;
use App\Models\Neighborhood;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$deleted = Neighborhood::where('name', 'Test')->orWhere('name', 'like', '%Test%')->delete();
echo "=== Cleaned up {$deleted} test records ===\n";

require_once __DIR__.'/dump_to_json.php';
