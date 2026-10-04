<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\WarehouseLocation;
use App\Http\Requests\StoreWarehouseLocationRequest;
use App\Http\Requests\UpdateWarehouseLocationRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class WarehouseLocationController extends Controller
{
    public function index(Warehouse $warehouse): View
    {
        $locations = $warehouse->locations()->paginate(15);

        return view('warehouse-locations.index', [
            'warehouse' => $warehouse,
            'locations' => $locations,
        ]);
    }

    public function create(Warehouse $warehouse): View
    {
        return view('warehouse-locations.create', [
            'warehouse' => $warehouse,
        ]);
    }

    public function store(StoreWarehouseLocationRequest $request, Warehouse $warehouse): RedirectResponse
    {
        $warehouse->locations()->create($request->validated());

        return redirect()->route('warehouse-locations.index', $warehouse)
            ->with('success', 'Warehouse location created successfully.');
    }

    public function show(Warehouse $warehouse, WarehouseLocation $location): View
    {
        return view('warehouse-locations.show', [
            'warehouse' => $warehouse,
            'location' => $location->load('stockMovements', 'inventories'),
        ]);
    }

    public function edit(Warehouse $warehouse, WarehouseLocation $location): View
    {
        return view('warehouse-locations.edit', [
            'warehouse' => $warehouse,
            'location' => $location,
        ]);
    }

    public function update(UpdateWarehouseLocationRequest $request, Warehouse $warehouse, WarehouseLocation $location): RedirectResponse
    {
        $location->update($request->validated());

        return redirect()->route('warehouse-locations.index', $warehouse)
            ->with('success', 'Warehouse location updated successfully.');
    }

    public function destroy(Warehouse $warehouse, WarehouseLocation $location): RedirectResponse
    {
        // Only allow deletion if no stock movements reference it
        if ($location->stockMovements()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete location with existing stock movements. Mark as inactive instead.');
        }

        $location->delete();

        return redirect()->route('warehouse-locations.index', $warehouse)
            ->with('success', 'Warehouse location deleted successfully.');
    }
}
