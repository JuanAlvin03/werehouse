<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActivityLog extends Model
{
    protected $table = 'activity_log';

    public function causer()
    {
        return $this->belongsTo(User::class, 'causer_id');
    }

    // Relationships
    /*
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'unit_id');
    }

    public function documentItems(): HasMany
    {
        return $this->hasMany(DocumentItem::class, 'unit_id');
    }
    */
}
