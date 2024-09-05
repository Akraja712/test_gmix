<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlansStoreRequest;
use App\Models\Plans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Plans::query();
        
        // Check if there's a search query
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('plan_name', 'like', "%$search%");
        }
        
        // Check if the request is AJAX
        if ($request->wantsJson()) {
            return response($query->get());
        }
        
        // Retrieve all plans if there's no search query
        $plans = $query->latest()->paginate(10);
        
        return view('plans.index')->with('plans', $plans);
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('plans.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    
    public function store(PlansStoreRequest $request)
    {
        $plans = Plans::create([
            'plan_name' => $request->plan_name,
            'validity' => $request->validity,
            'price' => $request->price,
            'save_amount' => $request->save_amount,
        ]);
    
        if (!$plans) {
            return redirect()->back()->with('error', 'Sorry, Something went wrong while creating user.');
        }
    
        return redirect()->route('plans.index')->with('success', 'Success, New plans has been added successfully!');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\plans  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Plans $plans)
    {

    }

    public function plans()
{
    return $this->belongsTo(Plans::class);
}
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Plans $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Plans $plans)
    {
        return view('plans.edit', compact('plans'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\plans  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plans $plans)

    {
        $plans->plan_name = $request->plan_name;
        $plans->validity = $request->validity;
        $plans->price = $request->price;
        $plans->save_amount = $request->save_amount;
        

        if (!$plans->save()) {
            return redirect()->back()->with('error', 'Sorry, Something went wrong while updating the customer.');
        }
        return redirect()->route('plans.edit', $plans->id)->with('success', 'Success, plans has been updated.');
    }

    public function destroy(Plans $plans)
    {
        $plans->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
