<?php
namespace App\Http\Controllers;

use App\Http\Requests\ProductsStoreRequest;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Products::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%$search%")
                  ->orWhere('unit', 'like', "%$search%")
                  ->orWhere('measurement', 'like', "%$search%")
                  ->orWhere('price', 'like', "%$search%")
                  ->orWhere('quantity', 'like', "%$search%");
        }

        if ($request->wantsJson()) {
            return response($query->get());
        }

        $products = $query->latest()->paginate(10);
        return view('products.index')->with('products', $products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductsStoreRequest $request)
    {

        // Check if a file has been uploaded
       if ($request->hasFile('image')) {
            $imageName = $request->file('image')->getClientOriginalName(); // Get the original file name
            $imagePath = $request->file('image')->storeAs('products', $imageName);
        } else {
            // Handle the case where no file has been uploaded
            $imagePath = null; // or provide a default image path
        }


        $products = Products::create([
            'name' => $request->name,
            'unit' => $request->unit,
            'measurement' => $request->measurement,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'image' => $imageName, 
        ]);

        if (!$products) {
            return redirect()->back()->with('error', 'Sorry, Something went wrong while creating product.');
        }
        return redirect()->route('products.index')->with('success', 'Success, New product has been added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Products $product)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Products $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Products $product)
    {
        $product->name = $request->name;
        $product->unit = $request->unit;
        $product->measurement = $request->measurement;
        $product->quantity = $request->quantity;
        $product->price = $request->price;

        if ($request->hasFile('image')) {
            $newImagePath = $request->file('image')->store('products', 'public');
            Storage::disk('public')->delete('products/' . $product->image);
            $product->image = basename($newImagePath);
        }
    
        if (!$product->save()) {
            return redirect()->back()->with('error', 'Sorry, Something went wrong while updating the product.');
        }
        return redirect()->route('products.edit', $product->id)->with('success', 'Success, product has been updated.');
    }
    

    public function destroy(Products $product)
    {

        if (Storage::disk('public')->exists('products/' . $product->image)) {
            Storage::disk('public')->delete('products/' . $product->image);
        }

        $product->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
