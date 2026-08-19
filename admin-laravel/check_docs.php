<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Procedure;

foreach (Procedure::all() as $p) {
    echo "ID {$p->id}: type=" . gettype($p->docs) . "\n";
    if (is_string($p->docs)) {
        echo "  String val: " . $p->docs . "\n";
        $decoded = json_decode($p->docs, true);
        if (is_array($decoded)) {
            $p->docs = $decoded;
        } else {
            $p->docs = [];
        }
        $p->save();
        echo "  Fixed to array!\n";
    }
}
