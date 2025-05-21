<?php

namespace App\Events;

use App\Models\Invoice;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvoiceRowUpdatedNow implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $invoice;

    /**
     * Create a new event instance.
     */
    public function __construct($id)
    {
        $this->invoice = Invoice::find($id);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        // Define el canal que usará el evento para emitir
        return new Channel("invoice.{$this->invoice->id}");
    }

    public function broadcastWith()
    {
        // Aquí puedes incluir los datos que deseas enviar al frontend
        return [
            'id' => $this->invoice->id,
            'total' => $this->invoice->total,
            'value_paid' => $this->invoice->value_paid,
            'value_glosa' => $this->invoice->value_glosa,
            'remaining_balance' => $this->invoice->remaining_balance,
            'status_xml' => $this->invoice->status_xml,
            'status_xml_backgroundColor' => $this->invoice->status_xml?->BackgroundColor(),
            'status_xml_description' => $this->invoice->status_xml?->description(),
            'path_xml' => $this->invoice->path_xml,
        ];
    }
}
