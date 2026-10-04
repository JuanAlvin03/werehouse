<?php

namespace App\Http\Controllers;

use App\Models\BusinessPartner;
use App\Http\Requests\StoreBusinessPartnerRequest;
use App\Http\Requests\UpdateBusinessPartnerRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BusinessPartnerController extends Controller
{
    public function index(): View
    {
        $partners = BusinessPartner::paginate(15);

        return view('business-partners.index', [
            'partners' => $partners,
        ]);
    }

    public function create(): View
    {
        return view('business-partners.create');
    }

    public function store(StoreBusinessPartnerRequest $request): RedirectResponse
    {
        BusinessPartner::create($request->validated());

        return redirect()->route('business-partners.index')
            ->with('success', 'Business partner created successfully.');
    }

    public function show(BusinessPartner $businessPartner): View
    {
        return view('business-partners.show', [
            'partner' => $businessPartner->load('documents'),
        ]);
    }

    public function edit(BusinessPartner $businessPartner): View
    {
        return view('business-partners.edit', [
            'partner' => $businessPartner,
        ]);
    }

    public function update(UpdateBusinessPartnerRequest $request, BusinessPartner $businessPartner): RedirectResponse
    {
        $businessPartner->update($request->validated());

        return redirect()->route('business-partners.index')
            ->with('success', 'Business partner updated successfully.');
    }

    public function destroy(BusinessPartner $businessPartner): RedirectResponse
    {
        // Only allow deletion if no documents reference it
        if ($businessPartner->documents()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete partner with existing documents. Mark as inactive instead.');
        }

        $businessPartner->delete();

        return redirect()->route('business-partners.index')
            ->with('success', 'Business partner deleted successfully.');
    }
}
