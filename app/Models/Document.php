<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Document extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'documents';

    protected $fillable = [
        'document_no',
        'document_type',
        'status',
        'document_date',
        'warehouse_id',
        'from_warehouse_id',
        'to_warehouse_id',
        'business_partner_id',
        'external_system',
        'external_reference',
        'idempotency_key',
        'notes',
        'created_by',
        'posted_by',
        'posted_at',
        'cancelled_at',
    ];

    protected $casts = [
        'document_date' => 'date',
        'posted_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['document_no', 'document_type', 'status', 'external_reference', 'posted_at', 'cancelled_at'])
            ->logOnlyDirty()
            ->useLogName('document');
    }

    // Constants
    const TYPE_RECEIPT = 'RECEIPT';
    const TYPE_ISSUE = 'ISSUE';
    const TYPE_ADJUSTMENT = 'ADJUSTMENT';
    const TYPE_CUSTOMER_RETURN = 'CUSTOMER_RETURN';
    const TYPE_SUPPLIER_RETURN = 'SUPPLIER_RETURN';
    const TYPE_TRANSFER = 'TRANSFER';

    const STATUS_DRAFT = 'DRAFT';
    const STATUS_POSTED = 'POSTED';
    const STATUS_CANCELLED = 'CANCELLED';

    public static function documentTypes()
    {
        return [
            self::TYPE_RECEIPT,
            self::TYPE_ISSUE,
            self::TYPE_ADJUSTMENT,
            self::TYPE_CUSTOMER_RETURN,
            self::TYPE_SUPPLIER_RETURN,
            self::TYPE_TRANSFER,
        ];
    }

    public static function statuses()
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_POSTED,
            self::STATUS_CANCELLED,
        ];
    }

    // Relationships
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function fromWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function businessPartner(): BelongsTo
    {
        return $this->belongsTo(BusinessPartner::class, 'business_partner_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(DocumentItem::class, 'document_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'document_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(DocumentAttachment::class, 'document_id');
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePosted($query)
    {
        return $query->where('status', self::STATUS_POSTED);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    public function scopeByExternalReference($query, $system, $reference)
    {
        return $query->where('external_system', $system)
            ->where('external_reference', $reference);
    }

    // Helpers
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isPosted(): bool
    {
        return $this->status === self::STATUS_POSTED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isTransfer(): bool
    {
        return $this->document_type === self::TYPE_TRANSFER;
    }
}
