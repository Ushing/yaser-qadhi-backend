<?php

namespace App\Observers;

use App\Models\DuaCategory;

class DuaCategoryObserver
{
    /**
     * Handle the DuaCategory "created" event.
     *
     * @param \App\Models\DuaCategory $duaCategory
     * @return void
     */

    public function creating(DuaCategory $duaCategory): void
    {
        if (is_null($duaCategory->position)) {
            $duaCategory->position = DuaCategory::max('position') + 1;
            return;
        }
        $lowerPriorityDuaCategories = DuaCategory::where('position', '>=', $duaCategory->position)->get();
        foreach ($lowerPriorityDuaCategories as $lowerPriorityDuaCategory) {
            $lowerPriorityDuaCategory->position++;
            $lowerPriorityDuaCategory->saveQuietly();
        }
    }

    public function updating(DuaCategory $duaCategory): void
    {
        if ($duaCategory->isClean('position')) {
            return;
        }

        if (is_null($duaCategory->position)) {
            $duaCategory->position = DuaCategory::max('position');
        }

        if ($duaCategory->getOriginal('position') > $duaCategory->position) {
            $positionRange = [
                $duaCategory->position, $duaCategory->getOriginal('position')
            ];
        } else {
            $positionRange = [
                $duaCategory->getOriginal('position'), $duaCategory->position
            ];
        }
        $lowerPriorityDuaCategories = DuaCategory::where('id', '!=', $duaCategory->id)
            ->whereBetween('position', $positionRange)
            ->get();

        foreach ($lowerPriorityDuaCategories as $lowerPrioritydDuaCategory) {
            if ($duaCategory->getOriginal('position') < $duaCategory->position) {
                $lowerPrioritydDuaCategory->position--;
            } else {
                $lowerPrioritydDuaCategory->position++;
            }
            $lowerPrioritydDuaCategory->saveQuietly();
        }
    }


    public function deleted(DuaCategory $duaCategory): void
    {
        $lowerPriorityDuaCategories = DuaCategory::where('position', '>', $duaCategory->position)->get();
        foreach ($lowerPriorityDuaCategories as $lowerPriorityDuaCategory) {
            $lowerPriorityDuaCategory->position--;
            $lowerPriorityDuaCategory->saveQuietly();
        }
    }

    /**
     * Handle the DuaCategory "restored" event.
     *
     * @param \App\Models\DuaCategory $duaCategory
     * @return void
     */
    public function restored(DuaCategory $duaCategory)
    {
        //
    }

    /**
     * Handle the DuaCategory "force deleted" event.
     *
     * @param \App\Models\DuaCategory $duaCategory
     * @return void
     */
    public function forceDeleted(DuaCategory $duaCategory)
    {
        //
    }
}
