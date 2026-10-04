<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class WarehouseController extends Controller
{
    public function index(): View
    {
        $warehouses = Warehouse::with('locations')
            ->paginate(15);

        return view('warehouses.index', [
            'warehouses' => $warehouses,
        ]);
    }

    public function create(): View
    {
        return view('warehouses.create');
    }

    public function store(StoreWarehouseRequest $request): RedirectResponse
    {
        $warehouse = Warehouse::create($request->validated());

        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse created successfully.');
    }

    public function show(Warehouse $warehouse): View
    {
        return view('warehouses.show', [
            'warehouse' => $warehouse->load('locations'),
        ]);
    }

    public function edit(Warehouse $warehouse): View
    {
        return view('warehouses.edit', [
            'warehouse' => $warehouse,
        ]);
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse): RedirectResponse
    {
        $warehouse->update($request->validated());

        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse updated successfully.');
    }

    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        // Only allow deletion if no transactions reference it
        if ($warehouse->documents()->count() > 0 || $warehouse->stockMovements()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete warehouse with existing transactions. Mark as inactive instead.');
        }

        $warehouse->delete();

        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse deleted successfully.');
    }
}
