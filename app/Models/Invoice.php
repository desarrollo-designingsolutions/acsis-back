<?php

namespace App\Models;

use App\Events\InvoiceRowUpdatedNow;
use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use Cacheable, HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function serviceVendor()
    {
        return $this->belongsTo(ServiceVendor::class, 'service_vendor_id');
    }

    public function invoice_payments()
    {
        return $this->hasMany(InvoicePayment::class);
    }

    public function tipoNota()
    {
        return $this->belongsTo(TipoNota::class, 'tipo_nota_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'invoice_id');
    }

    /**
     * Actualiza el total, value_paid y remaining_balance de la factura basado en
     * los servicios y pagos asociados, y dispara un evento para notificar al frontend.
     *
     * @param int $invoiceId
     * @return bool
     */
    public static function updateTotalFromServices($invoiceId): bool
    {
        $invoice = self::find($invoiceId);

        if (!$invoice) {
            return false;
        }

        // Calcular la suma de total_value de los servicios
        $total = $invoice->services()->sum('total_value') ?? 0;

        // Calcular la suma de value_paid de los pagos (excluyendo soft-deleted)
        $valuePaid = $invoice->invoice_payments()->sum('value_paid') ?? 0;

        // Calcular el saldo restante
        $remainingBalance = $total - $valuePaid;

        // Actualizar los campos en la factura
        $invoice->total = $total;
        $invoice->value_paid = $valuePaid;
        $invoice->remaining_balance = $remainingBalance;

        $saved = $invoice->save();

        if ($saved) {
            // Disparar el evento para notificar al frontend
            event(new InvoiceRowUpdatedNow($invoice->id));
        }

        return $saved;
    }
}
