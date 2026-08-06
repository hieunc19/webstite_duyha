<?php

use Illuminate\Contracts\Console\Kernel;
use App\Models\Neighborhood;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

echo "ID | TYPE | NAME | GROUP_CODE\n";
echo "-----------------------------------------\n";
foreach (Neighborhood::all() as $n) {
    echo "{$n->id} | {$n->type} | {$n->name} | {$n->group_code}\n";
}
