<?php
$jsonPath = __DIR__ . '/../client/public/boundaries.json';
if (!file_exists($jsonPath)) {
    die("File not found\n");
}
$data = json_decode(file_get_contents($jsonPath), true);
echo "Total keys: " . count($data) . "\n";
echo "First 10 keys:\n";
print_r(array_slice(array_keys($data), 0, 10));

$search = "Duy";
echo "\nKeys containing '$search':\n";
foreach (array_keys($data) as $key) {
    if (stripos($key, $search) !== false) {
        echo "- $key\n";
    }
}
