<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChangeInvoiceAuditData implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $invoice_id;

    public $data;

    public function __construct($invoice_id, $data)
    {
        $this->invoice_id = $invoice_id;
        $this->data = $data;
    }

    public function broadcastOn()
    {
        return new channel("invoice.{$this->invoice_id}");
    }

    public function broadcastWith()
    {
        return [
            'invoice_id' => $this->invoice_id,
            'data' => $this->data,
        ];
    }
}
