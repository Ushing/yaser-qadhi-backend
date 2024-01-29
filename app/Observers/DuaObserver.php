<?php

namespace App\Observers;

use App\Models\Dua;

class DuaObserver
{
    /**
     * Handle the Dua "created" event.
     *
     * @param \App\Models\Dua $dua
     * @return void
     */

    public function creating(Dua $dua): void
    {
        if (is_null($dua->position)) {
            $dua->position = Dua::max('position') + 1;
            return;
        }
        $lowerPriorityDuas = Dua::where('position', '>=', $dua->position)->get();
        foreach ($lowerPriorityDuas as $lowerPriorityDua) {
            $lowerPriorityDua->position++;
            $lowerPriorityDua->saveQuietly();
        }
    }

    public function updating(Dua $dua): void
    {
        if ($dua->isClean('position')) {
            return;
        }

        if (is_null($dua->position)) {
            $dua->position = Dua::max('position');
        }

        if ($dua->getOriginal('position') > $dua->position) {
            $positionRange = [
                $dua->position, $dua->getOriginal('position')
            ];
        } else {
            $positionRange = [
                $dua->getOriginal('position'), $dua->position
            ];
        }
        $lowerPriorityDuas = Dua::where('id', '!=', $dua->id)
            ->whereBetween('position', $positionRange)
            ->get();

        foreach ($lowerPriorityDuas as $lowerPrioritydDua) {
            if ($dua->getOriginal('position') < $dua->position) {
                $lowerPrioritydDua->position--;
            } else {
                $lowerPrioritydDua->position++;
            }
            $lowerPrioritydDua->saveQuietly();
        }
    }


    public function deleted(Dua $dua): void
    {
        $lowerPriorityDuas = Dua::where('position', '>', $dua->position)->get();
        foreach ($lowerPriorityDuas as $lowerPriorityDua) {
            $lowerPriorityDua->position--;
            $lowerPriorityDua->saveQuietly();
        }
    }

    /**
     * Handle the Dua "restored" event.
     *
     * @param \App\Models\Dua $dua
     * @return void
     */
    public function restored(Dua $dua)
    {
        //
    }

    /**
     * Handle the Dua "force deleted" event.
     *
     * @param \App\Models\Dua $dua
     * @return void
     */
    public function forceDeleted(Dua $dua)
    {
        //
    }
}
