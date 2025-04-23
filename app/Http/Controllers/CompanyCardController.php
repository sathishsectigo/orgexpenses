<?php

namespace App\Http\Controllers;

use App\Models\CompanyCard;
use Illuminate\Http\Request;

class CompanyCardController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage-company-cards');
    }

    public function index()
    {
        $companyCards = CompanyCard::all();
        return view('company_cards.index', compact('companyCards'));
    }

    public function create()
    {
        return view('company_cards.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'card_number' => 'required|unique:company_cards,card_number',
            'card_holder_name' => 'required',
            'bank_name' => 'required',
            'status' => 'required|in:active,inactive',
        ]);

        CompanyCard::create($request->all());
        return redirect()->route('company-cards.index')->with('success', 'Company Card added successfully.');
    }

    public function edit(CompanyCard $companyCard)
    {
        return view('company_cards.edit', compact('companyCard'));
    }

    public function update(Request $request, CompanyCard $companyCard)
    {
        $request->validate([
            'card_holder_name' => 'required',
            'bank_name' => 'required',
            'status' => 'required|in:active,inactive',
        ]);

        $companyCard->update($request->all());
        return redirect()->route('company-cards.index')->with('success', 'Company Card updated successfully.');
    }

    public function destroy(CompanyCard $companyCard)
    {
        if ($companyCard->expenses()->exists()) {
            return redirect()->route('company-cards.index')->with('error', 'Cannot delete: This card has associated expenses.');
        }

        $companyCard->delete();
        return redirect()->route('company-cards.index')->with('success', 'Company Card deleted successfully.');
    }
}
