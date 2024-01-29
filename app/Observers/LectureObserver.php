<?php

namespace App\Observers;

use App\Models\Lecture;

class LectureObserver
{
    /**
     * Handle the Lecture "created" event.
     *
     * @param \App\Models\Lecture $lecture
     * @return void
     */

    public function creating(Lecture $lecture): void
    {
        if (is_null($lecture->position)) {
            $lecture->position = Lecture::max('position') + 1;
            return;
        }
        $lowerPriorityLectures = Lecture::where('position', '>=', $lecture->position)->get();
        foreach ($lowerPriorityLectures as $lowerPriorityLecture) {
            $lowerPriorityLecture->position++;
            $lowerPriorityLecture->saveQuietly();
        }
    }

    public function updating(Lecture $lecture): void
    {
        if ($lecture->isClean('position')) {
            return;
        }

        if (is_null($lecture->position)) {
            $lecture->position = Lecture::max('position');
        }

        if ($lecture->getOriginal('position') > $lecture->position) {
            $positionRange = [
                $lecture->position, $lecture->getOriginal('position')
            ];
        } else {
            $positionRange = [
                $lecture->getOriginal('position'), $lecture->position
            ];
        }
        $lowerPriorityLectures = Lecture::where('id', '!=', $lecture->id)
            ->whereBetween('position', $positionRange)
            ->get();

        foreach ($lowerPriorityLectures as $lowerPrioritydLecture) {
            if ($lecture->getOriginal('position') < $lecture->position) {
                $lowerPrioritydLecture->position--;
            } else {
                $lowerPrioritydLecture->position++;
            }
            $lowerPrioritydLecture->saveQuietly();
        }
    }


    public function deleted(Lecture $lecture): void
    {
        $lowerPriorityLectures = Lecture::where('position', '>', $lecture->position)->get();
        foreach ($lowerPriorityLectures as $lowerPriorityLecture) {
            $lowerPriorityLecture->position--;
            $lowerPriorityLecture->saveQuietly();
        }
    }

    /**
     * Handle the Lecture "restored" event.
     *
     * @param \App\Models\Lecture $lecture
     * @return void
     */
    public function restored(Lecture $lecture)
    {
        //
    }

    /**
     * Handle the Lecture "force deleted" event.
     *
     * @param \App\Models\Lecture $lecture
     * @return void
     */
    public function forceDeleted(Lecture $lecture)
    {
        //
    }
}
