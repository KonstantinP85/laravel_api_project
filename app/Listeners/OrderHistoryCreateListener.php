<?php

namespace App\Listeners;

use App\Events\OrderEvent;
use App\Models\OrderHistory;

class OrderHistoryCreateListener
{
    /**
     * @param OrderEvent $event
     * @return void
     */
    public function handle(OrderEvent $event)
    {
        $data = $event->order->attributesToArray();
        OrderHistory::query()->create([
            'status' => $data['status'],
            'order_id' => $data['id']
        ]);
    }
}
