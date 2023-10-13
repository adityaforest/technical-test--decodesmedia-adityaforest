<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Activity;
use App\Events\InsertActivity;

class LogInsertActivity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(InsertActivity $event)
    {
        // dd('LogInsertActivity listener is executed');
        // Extract the data from the event
        $type = $event->type;
        $purchaseOrderId = $event->purchaseOrderId;
        $note = $event->note;
        $createdBy = $event->createdBy;

        // Create an activity record
        Activity::create([
            'type' => $type,
            'purchase_order_id' => $purchaseOrderId,
            'note' => $note,
            'created_by' => $createdBy
        ]);
    }
}
