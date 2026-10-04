<?php

namespace App\Services;

class DocumentNumberGenerator
{
    /**
     * Generate next document number based on type.
     */
    public static function generate($documentType): string
    {
        $prefix = self::getPrefixByType($documentType);
        
        // Get the last document number for this type
        $lastNumber = \App\Models\Document::byType($documentType)
            ->where('document_no', 'LIKE', $prefix . '%')
            ->latest('id')
            ->value('document_no');

        if (!$lastNumber) {
            $sequence = 1;
        } else {
            // Extract the numeric part and increment
            $numericPart = (int) substr($lastNumber, strlen($prefix));
            $sequence = $numericPart + 1;
        }

        return $prefix . str_pad($sequence, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get prefix for document type.
     */
    private static function getPrefixByType($type): string
    {
        return match ($type) {
            'RECEIPT' => 'GR-',
            'ISSUE' => 'GI-',
            'ADJUSTMENT' => 'ADJ-',
            'CUSTOMER_RETURN' => 'RET-C-',
            'SUPPLIER_RETURN' => 'RET-S-',
            'TRANSFER' => 'TRF-',
            default => 'DOC-',
        };
    }
}
