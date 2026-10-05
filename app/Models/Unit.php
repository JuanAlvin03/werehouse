<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Unit extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'units';

    protected $fillable = [
        'code',
        'name',
        'symbol',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['code', 'name', 'symbol'])
            ->logOnlyDirty()
            ->useLogName('unit');
    }

    // Relationships
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'unit_id');
    }

    public function documentItems(): HasMany
    {
        return $this->hasMany(DocumentItem::class, 'unit_id');
    }
}
