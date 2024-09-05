<?php

namespace App\Http\Controllers;

use App\Http\Requests\RewardProductsStoreRequest;
use App\Models\Reward_products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RewardproductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Reward_products::query();
        
        // Check if there's a search query
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('points', 'like', "%$search%");
            $query->where('name', 'like', "%$search%");
        }
        
        // Check if the request is AJAX
        if ($request->wantsJson()) {
            return response($query->get());
        }
        
        // Retrieve all points if there's no search query
        $reward_products = $query->latest()->paginate(10);
        
        return view('reward_products.index')->with('reward_products', $reward_products);
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('reward_products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    
    public function store(RewardProductsStoreRequest $request)
    {
            // Check if a file has been uploaded
       if ($request->hasFile('image')) {
        $imageName = $request->file('image')->getClientOriginalName(); // Get the original file name
        $imagePath = $request->file('image')->storeAs('reward_products', $imageName);
    } else {
        // Handle the case where no file has been uploaded
        $imagePath = null; // or provide a default image path
    }
        $reward_products = Reward_products::create([
            'points' => $request->points,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imageName, 
        ]);
    
        if (!$reward_products) {
            return redirect()->back()->with('error', 'Sorry, Something went wrong while creating user.');
        }
    
        return redirect()->route('reward_products.index')->with('success', 'Success, New Reward Products has been added successfully!');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\points  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(reward_products $reward_products)
    {

    }

    public function reward_products()
{
    return $this->belongsTo(Reward_products::class);
}
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Points $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Reward_products $reward_products)
    {
        return view('reward_products.edit', compact('reward_products'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Points  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reward_products $reward_products)

    {
        $reward_products->points = $request->points;
        $reward_products->name = $request->name;
        $reward_products->description = $request->description;

        if ($request->hasFile('image')) {
            $newImagePath = $request->file('image')->store('reward_products', 'public');
            Storage::disk('public')->delete('reward_products/' . $reward_products->image);
            $reward_products->image = basename($newImagePath);
        }
        

        if (!$reward_products->save()) {
            return redirect()->back()->with('error', 'Sorry, Something went wrong while updating the customer.');
        }
        return redirect()->route('reward_products.edit', $reward_products->id)->with('success', 'Success, Reward Products has been updated.');
    }

    public function destroy(Reward_products $reward_products)
    {
        if (Storage::disk('public')->exists('reward_products/' . $reward_products->image)) {
            Storage::disk('public')->delete('reward_products/' . $reward_products->image);
        }
        $reward_products->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
