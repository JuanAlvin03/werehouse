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

}
