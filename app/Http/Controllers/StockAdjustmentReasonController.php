<?php

namespace App\Http\Controllers;

use App\Models\StockAdjustmentReason;
use App\Http\Requests\StoreStockAdjustmentReasonRequest;
use App\Http\Requests\UpdateStockAdjustmentReasonRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StockAdjustmentReasonController extends Controller
{
    public function index(): View
    {
        $reasons = StockAdjustmentReason::paginate(15);

        return view('stock-adjustment-reasons.index', [
            'reasons' => $reasons,
        ]);
    }

    public function create(): View
    {
        return view('stock-adjustment-reasons.create');
    }

    public function store(StoreStockAdjustmentReasonRequest $request): RedirectResponse
    {
        StockAdjustmentReason::create($request->validated());

        return redirect()->route('stock-adjustment-reasons.index')
            ->with('success', 'Stock adjustment reason created successfully.');
    }

    public function show(StockAdjustmentReason $stockAdjustmentReason): View
    {
        return view('stock-adjustment-reasons.show', [
            'reason' => $stockAdjustmentReason,
        ]);
    }

    public function edit(StockAdjustmentReason $stockAdjustmentReason): View
    {
        return view('stock-adjustment-reasons.edit', [
            'reason' => $stockAdjustmentReason,
        ]);
    }

    public function update(UpdateStockAdjustmentReasonRequest $request, StockAdjustmentReason $stockAdjustmentReason): RedirectResponse
    {
        $stockAdjustmentReason->update($request->validated());

        return redirect()->route('stock-adjustment-reasons.index')
            ->with('success', 'Stock adjustment reason updated successfully.');
    }

    public function destroy(StockAdjustmentReason $stockAdjustmentReason): RedirectResponse
    {
        $stockAdjustmentReason->delete();

        return redirect()->route('stock-adjustment-reasons.index')
            ->with('success', 'Stock adjustment reason deleted successfully.');
    }
}
