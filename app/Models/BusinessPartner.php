<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class BusinessPartner extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'business_partners';

    protected $fillable = [
        'code',
        'name',
        'partner_type',
        'phone',
        'email',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['code', 'name', 'partner_type', 'phone', 'email', 'address', 'is_active'])
            ->logOnlyDirty()
            ->useLogName('business_partner');
    }

    // Relationships
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'business_partner_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('partner_type', $type);
    }

    public function scopeSuppliers($query)
    {
        return $query->where('partner_type', 'SUPPLIER')->orWhere('partner_type', 'BOTH');
    }

    public function scopeCustomers($query)
    {
        return $query->where('partner_type', 'CUSTOMER')->orWhere('partner_type', 'BOTH');
    }
}
