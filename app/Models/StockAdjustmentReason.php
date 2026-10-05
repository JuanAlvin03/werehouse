<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class StockAdjustmentReason extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'stock_adjustment_reasons';

    protected $fillable = [
        'code',
        'name',
        'direction',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['code', 'name', 'direction', 'is_active'])
            ->logOnlyDirty()
            ->useLogName('stock_adjustment_reason');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByDirection($query, $direction)
    {
        return $query->where(function ($q) use ($direction) {
            $q->where('direction', $direction)
                ->orWhere('direction', 'BOTH');
        });
    }
}
