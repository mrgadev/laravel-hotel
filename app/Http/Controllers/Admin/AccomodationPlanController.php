<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccomodationPlan;
use Illuminate\Http\Request;

class AccomodationPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accomdation_plans = AccomodationPlan::all();
        return view('dashboard.admin.accomdation-plan.index', compact('accomdation_plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.admin.accomdation-plan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'price' => 'required',
        ]);

        AccomodationPlan::create($data);
        return redirect()->route('dashboard.accomodation-plan.index')->with('success', 'Berhasil menambah data');
    }

    /**
     * Display the specified resource.
     */
    public function show(AccomodationPlan $accomodation_plan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccomodationPlan $accomodation_plan)
    {
        return view('dashboard.admin.accomdation-plan.edit', compact('accomodation_plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccomodationPlan $accomodation_plan)
    {
        $data = $request->validate([
            'name' => 'required',
            'price' => 'required',
        ]);

        $accomodation_plan->update($data);
        return redirect()->route('dashboard.accomodation-plan.index')->with('success', 'Berhasil mengubah data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccomodationPlan $accomodation_plan)
    {
        $accomodation_plan->delete();
        return redirect()->route('dashboard.accomodation-plan.index')->with('success', 'Berhasil menghapus data');
    }
}

