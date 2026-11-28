<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\BusinessPartner;
use App\Models\StockMovement;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\View\View;

use Barryvdh\DomPDF\Facade\Pdf;
//use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Http\FormRequest;

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
        return view('reports.create.stock-movement-by-warehouse', compact('warehouses'));
    }

    public function generate_stock_movement_by_warehouse(FormRequest $request): View
    {
        $warehouse = Warehouse::findOrFail($request->warehouse_id);
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $stock_movements = StockMovement::selectRaw('product_id, SUM(CASE WHEN quantity > 0 THEN quantity ELSE 0 END) AS qty_in, SUM(CASE WHEN quantity <= 0 THEN quantity ELSE 0 END) AS qty_out')
            ->where('movement_date', '>=', Carbon::parse($request->start_date))
            ->where('movement_date', '<=', Carbon::parse($request->end_date))
            ->where('warehouse_id', $warehouse->id)
            ->groupBy('product_id')
            ->get();

        return view('reports.show.stock-movement-by-warehouse', compact('warehouse', 'stock_movements', 'start_date', 'end_date'));
    }

    public function export_stock_movement_by_warehouse_pdf(FormRequest $request)
    {
        $warehouse = Warehouse::findOrFail($request->warehouse_id);
        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);
        $stock_movements = StockMovement::selectRaw('product_id, SUM(CASE WHEN quantity > 0 THEN quantity ELSE 0 END) AS qty_in, SUM(CASE WHEN quantity <= 0 THEN quantity ELSE 0 END) AS qty_out')
            ->where('movement_date', '>=', Carbon::parse($request->start_date))
            ->where('movement_date', '<=', Carbon::parse($request->end_date))
            ->where('warehouse_id', $warehouse->id)
            ->groupBy('product_id')
            ->get();

        return PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                ->loadView('exports.stock-movement-by-warehouse', [
                    'title' => $warehouse->name . '_' . $start_date . '_' . $end_date,
                    'stock_movements' => $stock_movements,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'warehouse' => $warehouse,
                ])
                ->setPaper('a4', 'potrait')
                ->stream($warehouse->name . '_' . $start_date . '_' . $end_date . '.pdf');
    }

    public function document_show($id)
    {
        $document = Document::findOrFail($id);

        return PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                ->loadView('exports.document-show', [
                    'title' => $document->document_type . '_' . $document->document_no,
                    'document' => $document,
                ])
                ->setPaper('a4', 'potrait')
                ->stream($document->document_type . '_' . $document->document_no . '.pdf');
    }

}
