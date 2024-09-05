<?php

namespace App\Http\Controllers;

use App\Http\Requests\PointsStoreRequest;
use App\Models\Points;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PointsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Points::query();
        
        // Check if there's a search query
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('points', 'like', "%$search%");
        }
        
        // Check if the request is AJAX
        if ($request->wantsJson()) {
            return response($query->get());
        }
        
        // Retrieve all points if there's no search query
        $points = $query->latest()->paginate(10);
        
        return view('points.index')->with('points', $points);
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('points.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    
    public function store(PointsStoreRequest $request)
    {
        $points = Points::create([
            'points' => $request->points,
            'offer_percentage' => $request->offer_percentage,
            'price' => $request->price,
            'datetime' => now(),
        ]);
    
        if (!$points) {
            return redirect()->back()->with('error', 'Sorry, Something went wrong while creating user.');
        }
    
        return redirect()->route('points.index')->with('success', 'Success, New Points has been added successfully!');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\points  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(points $points)
    {

    }

    public function points()
{
    return $this->belongsTo(Points::class);
}
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Points $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Points $points)
    {
        return view('points.edit', compact('points'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Points  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Points $points)

    {
        $points->points = $request->points;
        $points->offer_percentage = $request->offer_percentage;
        $points->price = $request->price;
        $points->datetime = now(); 
        

        if (!$points->save()) {
            return redirect()->back()->with('error', 'Sorry, Something went wrong while updating the customer.');
        }
        return redirect()->route('points.edit', $points->id)->with('success', 'Success, Points has been updated.');
    }

    public function destroy(Points $points)
    {
        $points->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
