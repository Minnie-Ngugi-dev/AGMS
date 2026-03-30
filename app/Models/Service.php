<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Service extends Model
{
    protected $fillable = [
        'vehicle_id',
        'mechanic_id',
        'job_card_no',
        'service_type',
        'status',
        'driver_name',
        'driver_phone',
        'mileage_in',
        'mileage_out',
        'service_date',
        'next_service_date',
        'next_service_km',
        'customer_complaint',
        'notes',
        'estimated_completion',
        'completed_at',
        'labour_charge',
        'discount',
        'vat_amount',
        'invoice_generated',
        'invoice_number',
        'invoice_date',
    ];

    protected $casts = [
        'service_date'         => 'date',
        'next_service_date'    => 'date',
        'estimated_completion' => 'date',
        'completed_at'         => 'datetime',
        'invoice_date'         => 'date',
        'invoice_generated'    => 'boolean',
        'labour_charge'        => 'decimal:2',
        'discount'             => 'decimal:2',
        'vat_amount'           => 'decimal:2',
    ];

    // ── RELATIONSHIPS ──────────────────────────────────────────────

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'mechanic_id');
    }

    public function checklist(): HasOne
    {
        return $this->hasOne(ServiceChecklist::class);
    }

    public function parts(): HasMany
    {
        return $this->hasMany(Part::class);
    }

    public function repairs(): HasMany
    {
        return $this->hasMany(Repair::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(ServiceOrderItem::class);
    }

    // ── COMPUTED ATTRIBUTES ────────────────────────────────────────

    /**
     * Total cost of all parts used
     */
    public function getPartsTotalAttribute(): float
    {
        return (float) $this->parts->sum(fn($p) => $p->quantity * $p->unit_price);
    }

    /**
     * Total cost of all repairs
     */
    public function getRepairsTotalAttribute(): float
    {
        return (float) $this->repairs->sum('cost');
    }

    /**
     * Total of service order items (from service catalog)
     */
    public function getOrderItemsTotalAttribute(): float
    {
        return (float) $this->orderItems->sum(fn($i) => $i->price * $i->quantity);
    }

    /**
     * Subtotal before VAT and discount
     */
    public function getSubtotalAttribute(): float
    {
        return $this->parts_total
            + $this->repairs_total
            + $this->order_items_total
            + (float) $this->labour_charge;
    }

    /**
     * Total cost including VAT minus discount
     */
    public function getTotalCostAttribute(): float
    {
        return $this->subtotal
            + (float) $this->vat_amount
            - (float) $this->discount;
    }

    /**
     * Total amount paid across all payments
     */
    public function getAmountPaidAttribute(): float
    {
        return (float) $this->payments->sum('amount');
    }

    /**
     * Remaining balance
     */
    public function getBalanceAttribute(): float
    {
        return max(0, $this->total_cost - $this->amount_paid);
    }

    /**
     * Payment status label
     */
    public function getPaymentStatusAttribute(): string
    {
        $paid  = $this->amount_paid;
        $total = $this->total_cost;

        if ($total <= 0)       return 'Unpaid';
        if ($paid <= 0)        return 'Unpaid';
        if ($paid >= $total)   return 'Paid';
        return 'Partial';
    }

    // ── STATIC HELPERS ─────────────────────────────────────────────

    /**
     * Generate a unique job card number e.g. JC-2026-0001
     */
    public static function generateJobCardNo(): string
    {
        $year   = date('Y');
        $prefix = "JC-{$year}-";
        $last   = static::where('job_card_no', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->value('job_card_no');

        $next = $last
            ? (int) substr($last, -4) + 1
            : 1;

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate a unique invoice number e.g. INV-2026-0001
     */
    public static function generateInvoiceNumber(): string
    {
        $settings = GarageSetting::get();
        $prefix   = ($settings->invoice_prefix ?? 'INV') . '-' . date('Y') . '-';
        $start    = $settings->invoice_start ?? 1;

        $last = static::where('invoice_number', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->value('invoice_number');

        $next = $last
            ? (int) substr($last, -4) + 1
            : $start;

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    // ── SCOPES ─────────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in-progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('service_date', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('service_date', now()->month)
                     ->whereYear('service_date', now()->year);
    }
}