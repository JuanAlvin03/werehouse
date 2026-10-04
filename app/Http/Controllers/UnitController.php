<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UnitController extends Controller
{
    public function index(): View
    {
        $units = Unit::paginate(15);

        return view('units.index', [
            'units' => $units,
        ]);
    }

    public function create(): View
    {
        return view('units.create');
    }

    public function store(StoreUnitRequest $request): RedirectResponse
    {
        Unit::create($request->validated());

        return redirect()->route('units.index')
            ->with('success', 'Unit created successfully.');
    }

    public function show(Unit $unit): View
    {
        return view('units.show', [
            'unit' => $unit->load('products'),
        ]);
    }

    public function edit(Unit $unit): View
    {
        return view('units.edit', [
            'unit' => $unit,
        ]);
    }

    public function update(UpdateUnitRequest $request, Unit $unit): RedirectResponse
    {
        $unit->update($request->validated());

        return redirect()->route('units.index')
            ->with('success', 'Unit updated successfully.');
    }

    public function destroy(Unit $unit): RedirectResponse
    {
        // Only allow deletion if no products reference it
        if ($unit->products()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete unit with existing products. Mark as inactive instead.');
        }

        $unit->delete();

        return redirect()->route('units.index')
            ->with('success', 'Unit deleted successfully.');
    }
}
