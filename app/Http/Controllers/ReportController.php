<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\BusinessPartner;
use App\Models\Unit;
use Illuminate\View\View;

use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{

    public function index(): View
    {
        $warehouses = Warehouse::all();
        $products = Product::all();
        $businessPartners = BusinessPartner::all();
        $units = Unit::all();

        return view('reports.index', compact('warehouses', 'products', 'businessPartners', 'units'));
    }

    public function create_stock_movement_by_warehouse(): View
    {
        $warehouses = Warehouse::all();
        return view('reports.stock-movement-by-warehouse', compact('warehouses'));
    }

    public function document_show($id)
    {
        $document = Document::findOrFail($id);

        return PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                ->loadView('admin.exports.document-show', [
                    'title' => $document->document_type . '_' . $document->document_no,
                    'document' => $document,
                ])
                ->setPaper('a4', 'potrait')
                ->stream($document->document_type . '_' . $document->document_no . '.pdf');
    }

}
