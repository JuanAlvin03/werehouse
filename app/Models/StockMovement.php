<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class StockMovement extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'stock_movements';

    protected $fillable = [
        'document_id',
        'document_item_id',
        'warehouse_id',
        'location_id',
        'product_id',
        'quantity',
        'movement_date',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'movement_date' => 'datetime',
    ];

    const UPDATED_AT = null;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['quantity', 'warehouse_id', 'product_id', 'movement_date'])
            ->logOnlyDirty()
            ->useLogName('stock_movement');
    }

    // Relationships
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function documentItem(): BelongsTo
    {
        return $this->belongsTo(DocumentItem::class, 'document_item_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class, 'location_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Scopes
    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    public function scopeByDocument($query, $documentId)
    {
        return $query->where('document_id', $documentId);
    }

    public function scopeIncoming($query)
    {
        return $query->where('quantity', '>', 0);
    }

    public function scopeOutgoing($query)
    {
        return $query->where('quantity', '<', 0);
    }
}
