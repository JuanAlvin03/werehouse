<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentItem extends Model
{
    use HasFactory;

    protected $table = 'document_items';

    protected $fillable = [
        'document_id',
        'product_id',
        'quantity',
        'unit_id',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
    ];

    const UPDATED_AT = null;

    // Relationships
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'document_item_id');
    }
}
