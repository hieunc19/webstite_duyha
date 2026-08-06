<?php

use Illuminate\Contracts\Console\Kernel;
use App\Models\Neighborhood;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

// Remove ID 27 or any duplicate old TDP that has same name as new TDP
Neighborhood::where('type', 'old')->where('name', 'TDP Ngọc Động')->delete();

echo "=== Cleaned up duplicate old records ===\n";

// Re-run check
echo "ID | TYPE | NAME | GROUP_CODE\n";
echo "-----------------------------------------\n";
foreach (Neighborhood::all() as $n) {
    echo "{$n->id} | {$n->type} | {$n->name} | {$n->group_code}\n";
}
