<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    @include('exports.partials.css')
</head>
<body>
    <div class="report-container">
        {{-- Header --}}
        <div class="doc-header">
            <div class="doc-header-left">
                <h1 class="doc-type">{{ $document->document_type }}</h1>
                <div class="doc-number">Document No.: <strong>{{ $document->document_no }}</strong></div>
            </div>
            <div class="doc-header-right">
                <span class="status-badge">{{ $document->status }}</span>
            </div>
        </div>

        {{-- Document Metadata & Locations --}}
        <div class="meta-grid">
            <div class="meta-col">
                <h2 class="section-label">Document Details</h2>
                <table class="info-table">
                    <tr>
                        <td class="info-key">Date</td>
                        <td class="info-val">{{ $document->document_date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td class="info-key">Creator</td>
                        <td class="info-val">{{ $document->createdBy?->name ?? '—' }}</td>
                    </tr>
                    @if ($document->business_partner_id)
                        <tr>
                            <td class="info-key">Business Partner</td>
                            <td class="info-val">{{ $document->businessPartner->name }}</td>
                        </tr>
                    @endif
                    @if ($document->external_reference)
                        <tr>
                            <td class="info-key">External Ref.</td>
                            <td class="info-val">{{ $document->external_reference }}</td>
                        </tr>
                    @endif
                    @if ($document->posted_at)
                        <tr>
                            <td class="info-key">Post Date</td>
                            <td class="info-val">{{ $document->posted_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="info-key">Posted By</td>
                            <td class="info-val">{{ $document->postedBy?->name ?? '—' }}</td>
                        </tr>
                    @endif
                    @if ($document->cancelled_at)
                        <tr>
                            <td class="info-key">Cancel Date</td>
                            <td class="info-val">{{ $document->cancelled_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @endif
                </table>
            </div>

            <div class="meta-col">
                <h2 class="section-label">Location</h2>
                @if ($document->document_type === 'TRANSFER')
                    <div class="location-block">
                        <div class="location-role">From</div>
                        <div class="location-name">{{ $document->fromWarehouse->name }}</div>
                        <div class="location-address">{{ $document->fromWarehouse->address }}</div>
                    </div>
                    <div class="location-block">
                        <div class="location-role">To</div>
                        <div class="location-name">{{ $document->toWarehouse->name }}</div>
                        <div class="location-address">{{ $document->toWarehouse->address }}</div>
                    </div>
                @else
                    <div class="location-block">
                        <div class="location-role">Warehouse</div>
                        <div class="location-name">{{ $document->warehouse->name }}</div>
                        <div class="location-address">{{ $document->warehouse->address }}</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Line Items --}}
        <h2 class="section-label">Items</h2>
        <table class="items-table">
            <thead>
                <tr>
                    <th class="col-sku">SKU</th>
                    <th class="col-product">Product</th>
                    <th class="col-qty">Qty</th>
                    <th class="col-unit">Unit</th>
                    <th class="col-notes">Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($document->items as $item)
                    <tr>
                        <td class="col-sku">{{ $item->product->sku }}</td>
                        <td class="col-product">{{ $item->product->name }}</td>
                        <td class="col-qty">{{ ($item->direction == 'DECREASE' ? '-' : '+') . number_format($item->quantity, 3) }}</td>
                        <td class="col-unit">{{ $item->unit->code }}</td>
                        <td class="col-notes">{{ $item->notes }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Document Notes --}}
        @if ($document->notes)
            <div class="notes-box">
                <h2 class="section-label">Notes</h2>
                <p class="notes-content">{{ $document->notes }}</p>
            </div>
        @endif
    </div>
</body>
</html>