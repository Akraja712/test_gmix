<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Orders;
use App\Models\Products;
use App\Models\Addresses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\OrderStoreRequest;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $query = Orders::query()->with('user', 'addresses', 'product'); // Eager load relationships
    
        // Check if there's a search query
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('addresses', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('price', 'like', "%{$search}%")
              ->orWhere('delivery_charges', 'like', "%{$search}%")
              ->orWhere('payment_mode', 'like', "%{$search}%");
        }
    
        // Paginate the results
        $orders = $query->latest()->paginate(10);
    
        $users = Users::all(); // Fetch all users for the filter dropdown
        $products = Products::all(); // Fetch all products for the filter dropdown
    
        return view('orders.index', compact('users', 'orders', 'products')); // Pass users, orders, and products to the view
    }
    public function create()
{
    $products = Products::all();
    $users = Users::all();
    $addresses = Addresses::all();
    
    return view('orders.create', compact('products', 'users','addresses'));
}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    
    public function store(OrderStoreRequest $request)
    {
        $orders = Orders::create([
            'user_id' => $request->user_id,
            'address_id' => $request->address_id,
            'product_id' => $request->product_id,
            'price' => $request->price,
            'delivery_charges' => $request->delivery_charges,
            'payment_mode' => $request->payment_mode,
        ]);
    
        if (!$orders) {
            return redirect()->back()->with('error', 'Sorry, Something went wrong while creating user.');
        }
    
        return redirect()->route('orders.index')->with('success', 'Success, New orders has been added successfully!');
    }
    public function getUserAddresses($userId)
    {
        // Fetch addresses associated with the user
        $addresses = Addresses::where('user_id', $userId)->get(['id', 'name']);
    
        // Return the addresses as a JSON response
        return response()->json($addresses);
    }
    
}
