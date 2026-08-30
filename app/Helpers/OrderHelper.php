<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class OrderHelper
{
    /**
     * Re-sequence all records in a model/table to ensure they are 1..N.
     */
    public static function normalize(string $modelClass, array $scopes = []): void
    {
        DB::transaction(function () use ($modelClass, $scopes) {
            $query = $modelClass::query();
            foreach ($scopes as $column => $value) {
                $query->where($column, $value);
            }
            
            $items = $query->orderBy('order')->orderBy('id')->get();
            
            foreach ($items as $index => $item) {
                $newOrder = $index + 1;
                if ($item->order !== $newOrder) {
                    $item->timestamps = false; // Disable timestamps update
                    $item->update(['order' => $newOrder]);
                }
            }
        });
    }

    /**
     * Move an item to a specific position and re-sequence.
     */
    public static function moveTo($modelInstance, int $targetOrder, array $scopes = []): void
    {
        DB::transaction(function () use ($modelInstance, $targetOrder, $scopes) {
            $modelClass = get_class($modelInstance);
            
            // 1. Fetch all other items in scope (excluding the current one)
            $query = $modelClass::query()->where('id', '!=', $modelInstance->id);
            foreach ($scopes as $column => $value) {
                $query->where($column, $value);
            }
            $items = $query->orderBy('order')->orderBy('id')->get()->all();
            
            // 2. Clamp target order to valid range [1, count + 1]
            $count = count($items);
            if ($targetOrder < 1) {
                $targetOrder = 1;
            }
            if ($targetOrder > $count + 1) {
                $targetOrder = $count + 1;
            }
            
            // 3. Insert current item at Y - 1 index
            array_splice($items, $targetOrder - 1, 0, [$modelInstance]);
            
            // 4. Update all orders sequentially
            foreach ($items as $index => $item) {
                $newOrder = $index + 1;
                if ($item->order !== $newOrder) {
                    $item->timestamps = false;
                    $item->update(['order' => $newOrder]);
                }
            }
        });
    }
}
