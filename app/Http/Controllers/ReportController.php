<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\BusinessPartner;
use App\Models\Unit;
use Illuminate\View\View;

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

}
