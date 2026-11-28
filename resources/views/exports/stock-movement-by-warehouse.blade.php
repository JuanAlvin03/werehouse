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
                <h1 class="doc-type">Stock Movement Report</h1>
            </div>
        </div>

        {{-- Document Metadata & Locations --}}
        <div class="meta-grid">
            <div class="meta-col">
                <h2 class="section-label">Document Details</h2>
                <table class="info-table">
                    <tr>
                        <td class="info-key">Start Date</td>
                        <td class="info-val">{{ $start_date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td class="info-key">End Date</td>
                        <td class="info-val">{{ $end_date->format('M d, Y') }}</td>
                    </tr>
                </table>
            </div>

            <div class="meta-col">
                <h2 class="section-label">Location</h2>
                <div class="location-block">
                    <div class="location-role">Warehouse</div>
                    <div class="location-name">{{ $warehouse->name }}</div>
                    <div class="location-address">{{ $warehouse->address }}</div>
                </div>
            </div>
        </div>

        {{-- Line Items --}}
        <h2 class="section-label">Items</h2>
        <table class="items-table">
            <thead>
                <tr>
                    <th class="col-sku">SKU</th>
                    <th class="col-product">Product</th>
                    <th class="col-unit">Unit</th>
                    <th class="col-qty">Qty In</th>
                    <th class="col-qty">Qty Out</th>
                    <th class="col-qty">Net Change</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stock_movements as $item)
                    <tr>
                        <td class="col-sku">{{ $item->product->sku }}</td>
                        <td class="col-product">{{ $item->product->name }}</td>
                        <td class="col-unit">{{ $item->product->unit->code }}</td>
                        <td class="col-qty">{{ '+' . number_format($item->qty_in, 3) }}</td>
                        <td class="col-qty">{{ number_format($item->qty_out, 3) }}</td>
                        <td class="col-qty">{{ ($item->qty_in + $item->qty_out > 0 ? '+' : '') . number_format($item->qty_in + $item->qty_out, 3) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>