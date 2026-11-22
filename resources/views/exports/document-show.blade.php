<html>
    <head>
        <title>{{$title}}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        @include('exports.partials.css')
    </head>
        <body>
            <span class="font-16 bold">{{ $document->document_type }}</span>
            
            <br>

            <table>
                <tr>
                    <td>Document No.</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td></td>
                </tr>
                @if ($document->business_partner_id)
                    <tr>
                        <td>Business Partner</td>
                        <td>{{ $document->businessPartner->name }}</td>
                    </tr>
                @endif
                @if ($document->external_reference)
                    <tr>
                        <td>External Reference</td>
                        <td>{{ $document->external_reference }}</td>
                    </tr>
                @endif
            </table>

            @if ($document->document_type === 'TRANSFER')
                From:
                {{ $document->fromWarehouse->name }}
                {{ $document->fromWarehouse->address }}
                <br>
                To:
                {{ $document->toWarehouse->name }}
                {{ $document->toWarehouse->address }}
                <br>
            @else
                Warehouse:
                {{ $document->warehouse->name }}
                <br>
                {{ $document->warehouse->address }}
                <br>
            @endif

            Date: {{ $document->document_date->format('M d, Y') }}
            <br>
            Creator:
            {{ $document->createdBy?->name }}
            <br>

            @if ($document->posted_at)
                Post Date:
                {{ $document->posted_at->format('M d, Y H:i') }}
                <br>
                Posted by:
                {{ $document->postedBy?->name }}
                <br>
            @endif

            @if($document->cancelled_at)
                Cancel Date:
                {{ $document->cancelled_at->format('M d, Y H:i') }}
                <br>
            @endif

            {{ $document->notes }}

            {{-- label for products / items --}}

            <table>
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($document->items as $item)
                        <tr>
                            <td>{{ $item->product->sku }}</td>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ number_format($item->quantity, 3) }}</td>
                            <td>{{ $item->unit->code }}</td>
                            <td>{{ $item->notes }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            
        </body>
</html>