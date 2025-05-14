<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoicePayment extends Model
{
    use Cacheable, HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];

    /**
     * Boot del modelo para registrar eventos.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($invoicePayment) {
            Invoice::updateTotalFromServices($invoicePayment->invoice_id);
        });

        static::updated(function ($invoicePayment) {
            Invoice::updateTotalFromServices($invoicePayment->invoice_id);
        });

        static::deleted(function ($invoicePayment) {
            Invoice::updateTotalFromServices($invoicePayment->invoice_id);
        });

        static::saved(function ($invoicePayment) {
            Invoice::updateTotalFromServices($invoicePayment->invoice_id);
        });
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
