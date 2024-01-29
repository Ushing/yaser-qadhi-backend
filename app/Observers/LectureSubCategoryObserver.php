<?php

namespace App\Observers;

use App\Models\LectureSubCategory;

class LectureSubCategoryObserver
{
    /**
     * Handle the LectureSubCategory "created" event.
     *
     * @param \App\Models\LectureSubCategory $lectureSubCategory
     * @return void
     */

    public function creating(LectureSubCategory $lectureSubCategory): void
    {
        if (is_null($lectureSubCategory->position)) {
            $lectureSubCategory->position = LectureSubCategory::max('position') + 1;
            return;
        }
        $lowerPriorityLectureSubCategories = LectureSubCategory::where('position', '>=', $lectureSubCategory->position)->get();
        foreach ($lowerPriorityLectureSubCategories as $lowerPriorityLectureSubCategory) {
            $lowerPriorityLectureSubCategory->position++;
            $lowerPriorityLectureSubCategory->saveQuietly();
        }
    }

    public function updating(LectureSubCategory $lectureSubCategory): void
    {
        if ($lectureSubCategory->isClean('position')) {
            return;
        }

        if (is_null($lectureSubCategory->position)) {
            $lectureSubCategory->position = LectureSubCategory::max('position');
        }

        if ($lectureSubCategory->getOriginal('position') > $lectureSubCategory->position) {
            $positionRange = [
                $lectureSubCategory->position, $lectureSubCategory->getOriginal('position')
            ];
        } else {
            $positionRange = [
                $lectureSubCategory->getOriginal('position'), $lectureSubCategory->position
            ];
        }
        $lowerPriorityLectureSubCategories = LectureSubCategory::where('id', '!=', $lectureSubCategory->id)
            ->whereBetween('position', $positionRange)
            ->get();

        foreach ($lowerPriorityLectureSubCategories as $lowerPriorityLectureSubCategory) {
            if ($lectureSubCategory->getOriginal('position') < $lectureSubCategory->position) {
                $lowerPriorityLectureSubCategory->position--;
            } else {
                $lowerPriorityLectureSubCategory->position++;
            }
            $lowerPriorityLectureSubCategory->saveQuietly();
        }
    }


    public function deleted(LectureSubCategory $lectureSubCategory): void
    {
        $lowerPriorityLectureSubCategories = LectureSubCategory::where('position', '>', $lectureSubCategory->position)->get();
        foreach ($lowerPriorityLectureSubCategories as $lowerPriorityLectureSubCategory) {
            $lowerPriorityLectureSubCategory->position--;
            $lowerPriorityLectureSubCategory->saveQuietly();
        }
    }

    /**
     * Handle the LectureSubCategory "restored" event.
     *
     * @param \App\Models\LectureSubCategory $lectureSubCategory
     * @return void
     */
    public function restored(LectureSubCategory $lectureSubCategory)
    {
        //
    }

    /**
     * Handle the LectureSubCategory "force deleted" event.
     *
     * @param \App\Models\LectureSubCategory $lectureSubCategory
     * @return void
     */
    public function forceDeleted(LectureSubCategory $lectureSubCategory)
    {
        //
    }
}
