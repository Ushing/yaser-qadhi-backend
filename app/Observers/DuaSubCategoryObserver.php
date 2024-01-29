<?php

namespace App\Observers;

use App\Models\DuaSubCategory;

class DuaSubCategoryObserver
{
    /**
     * Handle the DuaSubCategory "created" event.
     *
     * @param \App\Models\DuaSubCategory $duaSubCategory
     * @return void
     */

    public function creating(DuaSubCategory $duaSubCategory): void
    {
        if (is_null($duaSubCategory->position)) {
            $duaSubCategory->position = DuaSubCategory::max('position') + 1;
            return;
        }
        $lowerPriorityDuaSubCategories = DuaSubCategory::where('position', '>=', $duaSubCategory->position)->get();
        foreach ($lowerPriorityDuaSubCategories as $lowerPriorityDuaSubCategory) {
            $lowerPriorityDuaSubCategory->position++;
            $lowerPriorityDuaSubCategory->saveQuietly();
        }
    }

    public function updating(DuaSubCategory $duaSubCategory): void
    {
        if ($duaSubCategory->isClean('position')) {
            return;
        }

        if (is_null($duaSubCategory->position)) {
            $duaSubCategory->position = DuaSubCategory::max('position');
        }

        if ($duaSubCategory->getOriginal('position') > $duaSubCategory->position) {
            $positionRange = [
                $duaSubCategory->position, $duaSubCategory->getOriginal('position')
            ];
        } else {
            $positionRange = [
                $duaSubCategory->getOriginal('position'), $duaSubCategory->position
            ];
        }
        $lowerPriorityDuaSubCategories = DuaSubCategory::where('id', '!=', $duaSubCategory->id)
            ->whereBetween('position', $positionRange)
            ->get();

        foreach ($lowerPriorityDuaSubCategories as $lowerPrioritydDuaSubCategory) {
            if ($duaSubCategory->getOriginal('position') < $duaSubCategory->position) {
                $lowerPrioritydDuaSubCategory->position--;
            } else {
                $lowerPrioritydDuaSubCategory->position++;
            }
            $lowerPrioritydDuaSubCategory->saveQuietly();
        }
    }


    public function deleted(DuaSubCategory $duaSubCategory): void
    {
        $lowerPriorityDuaSubCategories = DuaSubCategory::where('position', '>', $duaSubCategory->position)->get();
        foreach ($lowerPriorityDuaSubCategories as $lowerPriorityDuaSubCategory) {
            $lowerPriorityDuaSubCategory->position--;
            $lowerPriorityDuaSubCategory->saveQuietly();
        }
    }

    /**
     * Handle the DuaSubCategory "restored" event.
     *
     * @param \App\Models\DuaSubCategory $duaSubCategory
     * @return void
     */
    public function restored(DuaSubCategory $duaSubCategory)
    {
        //
    }

    /**
     * Handle the DuaSubCategory "force deleted" event.
     *
     * @param \App\Models\DuaSubCategory $duaSubCategory
     * @return void
     */
    public function forceDeleted(DuaSubCategory $duaSubCategory)
    {
        //
    }
}
