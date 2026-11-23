<style>
    /* Minimalist PDF Report / Document Stylesheet */
    @page {
        size: A4 portrait;
        margin: 22mm 18mm 22mm 18mm;
    }
    
    * {
        box-sizing: border-box;
    }
    
    body {
        margin: 0;
        padding: 0;
        font-family: "DejaVu Sans", Arial, Helvetica, sans-serif;
        font-size: 11px;
        line-height: 1.45;
        color: #1f2937;
        background-color: #ffffff;
    }
    
    .report-container {
        width: 100%;
    }
    
    /* Header */
    .doc-header {
        display: table;
        width: 100%;
        padding-bottom: 14px;
        margin-bottom: 18px;
        border-bottom: 1.5px solid #111827;
    }
    
    .doc-header-left,
    .doc-header-right {
        display: table-cell;
        vertical-align: top;
    }
    
    .doc-header-right {
        text-align: right;
    }
    
    .doc-type {
        font-size: 18px;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #111827;
        margin: 0 0 4px 0;
    }
    
    .doc-number {
        font-size: 12px;
        color: #4b5563;
    }
    
    .status-badge {
        display: inline-block;
        padding: 3px 8px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: #111827;
        border: 1px solid #9ca3af;
        border-radius: 3px;
    }
    
    /* Metadata & Location Grid */
    .meta-grid {
        display: table;
        width: 100%;
        margin-bottom: 20px;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .meta-col {
        display: table-cell;
        width: 50%;
        vertical-align: top;
        padding-right: 16px;
    }
    
    .meta-col:last-child {
        padding-right: 0;
        padding-left: 16px;
    }
    
    .section-label {
        font-size: 9.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #6b7280;
        margin: 0 0 6px 0;
        padding-bottom: 3px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 12px;
    }
    
    .info-table td {
        padding: 3px 0;
        vertical-align: top;
    }
    
    .info-key {
        width: 38%;
        color: #6b7280;
        font-size: 10.5px;
    }
    
    .info-val {
        width: 62%;
        color: #111827;
        font-weight: 500;
    }
    
    .location-block {
        margin-bottom: 10px;
    }
    
    .location-role {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        color: #4b5563;
    }
    
    .location-name {
        font-weight: 700;
        color: #111827;
    }
    
    .location-address {
        color: #4b5563;
        font-size: 10.5px;
    }
    
    /* Items Table */
    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 6px;
        margin-bottom: 20px;
    }
    
    .items-table thead {
        display: table-header-group;
    }
    
    .items-table tr {
        page-break-inside: avoid;
    }
    
    .items-table th {
        font-size: 9.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #374151;
        background-color: #f9fafb;
        padding: 8px 10px;
        text-align: left;
        border-top: 1px solid #d1d5db;
        border-bottom: 1.5px solid #374151;
    }
    
    .items-table td {
        padding: 8px 10px;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: top;
        color: #1f2937;
    }
    
    .items-table tbody tr:last-child td {
        border-bottom: 1.5px solid #374151;
    }
    
    .col-sku {
        width: 15%;
        font-family: "DejaVu Sans Mono", monospace;
        font-size: 10px;
        color: #374151;
    }
    
    .col-product {
        width: 32%;
        font-weight: 500;
    }
    
    .col-qty {
        width: 14%;
        text-align: right !important;
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
    }
    
    .col-unit {
        width: 10%;
        text-align: center !important;
        white-space: nowrap;
    }
    
    .col-notes {
        width: 29%;
        color: #4b5563;
        font-size: 10.5px;
    }
    
    /* Notes Section */
    .notes-box {
        margin-top: 12px;
        padding: 10px 12px;
        background-color: #f9fafb;
        border-left: 3px solid #9ca3af;
        page-break-inside: avoid;
    }
    
    .notes-box .section-label {
        border-bottom: none;
        margin-bottom: 3px;
        padding-bottom: 0;
    }
    
    .notes-content {
        margin: 0;
        color: #374151;
        white-space: pre-line;
    }
</style>