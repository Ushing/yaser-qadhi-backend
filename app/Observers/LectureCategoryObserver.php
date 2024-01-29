<?php

namespace App\Observers;

use App\Models\LectureCategory;

class LectureCategoryObserver
{
    /**
     * Handle the LectureCategory "created" event.
     *
     * @param \App\Models\LectureCategory $lectureCategory
     * @return void
     */

    public function creating(LectureCategory $lectureCategory): void
    {
        if (is_null($lectureCategory->position)) {
            $lectureCategory->position = LectureCategory::max('position') + 1;
            return;
        }
        $lowerPriorityLectureCategories = LectureCategory::where('position', '>=', $lectureCategory->position)->get();
        foreach ($lowerPriorityLectureCategories as $lowerPriorityLectureCategory) {
            $lowerPriorityLectureCategory->position++;
            $lowerPriorityLectureCategory->saveQuietly();
        }
    }

    public function updating(LectureCategory $lectureCategory): void
    {
        if ($lectureCategory->isClean('position')) {
            return;
        }

        if (is_null($lectureCategory->position)) {
            $lectureCategory->position = LectureCategory::max('position');
        }

        if ($lectureCategory->getOriginal('position') > $lectureCategory->position) {
            $positionRange = [
                $lectureCategory->position, $lectureCategory->getOriginal('position')
            ];
        } else {
            $positionRange = [
                $lectureCategory->getOriginal('position'), $lectureCategory->position
            ];
        }
        $lowerPriorityLectureCategories = LectureCategory::where('id', '!=', $lectureCategory->id)
            ->whereBetween('position', $positionRange)
            ->get();

        foreach ($lowerPriorityLectureCategories as $lowerPriorityLectureCategory) {
            if ($lectureCategory->getOriginal('position') < $lectureCategory->position) {
                $lowerPriorityLectureCategory->position--;
            } else {
                $lowerPriorityLectureCategory->position++;
            }
            $lowerPriorityLectureCategory->saveQuietly();
        }
    }


    public function deleted(LectureCategory $lectureCategory): void
    {
        $lowerPriorityLectureCategories = LectureCategory::where('position', '>', $lectureCategory->position)->get();
        foreach ($lowerPriorityLectureCategories as $lowerPriorityLectureCategory) {
            $lowerPriorityLectureCategory->position--;
            $lowerPriorityLectureCategory->saveQuietly();
        }
    }

    /**
     * Handle the LectureCategory "restored" event.
     *
     * @param \App\Models\LectureCategory $lectureCategory
     * @return void
     */
    public function restored(LectureCategory $lectureCategory)
    {
        //
    }

    /**
     * Handle the LectureCategory "force deleted" event.
     *
     * @param \App\Models\LectureCategory $lectureCategory
     * @return void
     */
    public function forceDeleted(LectureCategory $lectureCategory)
    {
        //
    }
}
