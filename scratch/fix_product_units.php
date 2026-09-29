<?php

use App\Models\Product;
use App\Models\Unit;

$products = Product::whereNotNull('unit_id')->get();
$count = 0;

foreach ($products as $p) {
    if (!is_numeric($p->unit_id)) {
        $name = trim($p->unit_id);
        if ($name !== '') {
            $unit = Unit::firstOrCreate(['name' => $name]);
            $p->unit_id = (string) $unit->id;
            $p->save();
            echo "Updated Product ID {$p->id} ({$p->item_name}): unit_id '{$name}' -> {$unit->id}\n";
            $count++;
        }
    }
}

echo "Finished cleanup. Updated {$count} products.\n";
