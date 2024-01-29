<?php

namespace App\Listeners;

use App\Events\HajjProfileCreateEvent;
use App\Models\HajjStatus;
use App\Models\HajjSublist;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HajjProfileCreateListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\HajjProfileCreateEvent $event
     * @return false
     */
    public function handle(HajjProfileCreateEvent $event)
    {
        $hajjSubLists = HajjSublist::orderBy('id', 'asc')->get(['id', 'checklist_id']);
        if (count($hajjSubLists) != 0) {
            foreach ($hajjSubLists as $hajjSubList) {
                HajjStatus::create([
                    'entry_date' => null,
                    'status' => 'unselect',
                    'isExecuted' => 'false',
                    'profile_id' => $event->profileData->id,
                    'customer_id' => $event->profileData->customer_id,
                    'sublist_id' => $hajjSubList->id,
                    'checklist_id' => $hajjSubList->checklist_id,
                ]);
            }
        } else {
            return false;
        }
        return true;

    }
}
