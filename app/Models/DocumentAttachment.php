<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentAttachment extends Model
{
    use HasFactory;

    protected $table = 'document_attachments';

    protected $fillable = [
        'document_id',
        'file_name',
        'file_path',
        'mime_type',
    ];

    const UPDATED_AT = null;

    // Relationships
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}
