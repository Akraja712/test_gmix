<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Orders;
use App\Models\Products;
use App\Models\Addresses;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\OrderStoreRequest;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;


class OrdersController extends Controller
{

    public function verify(Request $request)
    {
        $orderIds = $request->input('order_ids', []);
        $newStatus = $request->input('status'); // Get the status from the request
    
        foreach ($orderIds as $orderId) {
            $order = Orders::find($orderId);
            if ($order) {
                // Update the order status to the selected status
                $order->status = $newStatus;
    
                // Execute Shiprocket API request only if the status is 'Confirmed' (1) and ship_rocket is not already 1
                if ($newStatus == 1 && $order->ship_rocket != 1) {
                    // Fetch necessary data
                    $address = Addresses::find($order->address_id);
                    $product = Products::find($order->product_id);
                    $price = $order->price;
                    $delivery_charges = $order->delivery_charges;
                    $total_price = $order->price;
                    $address1 = $address->door_no . ' ' . $address->street_name;
                    $payment_mode = $order->payment_mode;
    
                    // Shiprocket API request
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjUwOTY4OTAsInNvdXJjZSI6InNyLWF1dGgtaW50IiwiZXhwIjoxNzI1OTIwMjcyLCJqdGkiOiJ6VFFtdjV4RWRrNE1IbTdLIiwiaWF0IjoxNzI1MDU2MjcyLCJpc3MiOiJodHRwczovL3NyLWF1dGguc2hpcHJvY2tldC5pbi9hdXRob3JpemUvdXNlciIsIm5iZiI6MTcyNTA1NjI3MiwiY2lkIjoyNzI4MzUyLCJ0YyI6MzYwLCJ2ZXJib3NlIjpmYWxzZSwidmVuZG9yX2lkIjowLCJ2ZW5kb3JfY29kZSI6IiJ9.sLpaoPK_vihXBiFO6ivYzXk6WX9-iORL28RYzz8UPxY'
                    ])->post('https://apiv2.shiprocket.in/v1/external/orders/create/adhoc', [
                        "order_id" => "Gmix-" . $orderId, // Use current order ID with prefix "Gmix-"
                        "order_date" => Carbon::now()->format('Y-m-d H:i:s'),
                        "pickup_location" => "Trichy",
                        "channel_id" => "",
                        "comment" => "G Mix",
                        "billing_customer_name" => $address->first_name,
                        "billing_last_name" => $address->last_name,
                        "billing_address" => $address1,
                        "billing_address_2" => "",
                        "billing_city" => $address->city,
                        "billing_pincode" => $address->pincode,
                        "billing_state" => $address->state,
                        "billing_country" => "India",
                        "billing_email" => "",
                        "billing_phone" => $address->mobile,
                        "shipping_is_billing" => true,
                        "order_items" => [
                            [
                                "name" => "G Mix",
                                "sku" => "123456",
                                "units" => 1,
                                "selling_price" => $price,
                                "discount" => "",
                                "tax" => "",
                                "hsn" => 441122
                            ]
                        ],
                        "payment_method" => $payment_mode,
                        "shipping_charges" => (int) $delivery_charges,
                        "giftwrap_charges" => 0,
                        "transaction_charges" => 0,
                        "total_discount" => 0,
                        "sub_total" => (int) $total_price,
                        "length" => 8,
                        "breadth" => 4,
                        "height" => 5,
                        "weight" => 0.5
                    ]);
    
                    if ($response->successful()) {
                        // If the API request is successful, update the ship_rocket status
                        $order->ship_rocket = 1;
                        $order->save();
                    } else {
                        // If the API request fails, return an error response
                        return response()->json(['success' => false, 'message' => 'Shiprocket API request failed'], 500);
                    }
                } else {
                    // If status is not "Confirmed" or ship_rocket is already 1, just save the order status
                    $order->save();
                }
            }
        }
    
        return response()->json(['success' => true]);
    }
    
    
    public function index(Request $request)
{
    $query = Orders::query()->with('user', 'addresses', 'product'); // Eager load relationships

    // Search functionality
    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where(function($q) use ($search) {
            $q->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('addresses', function($q) use ($search) {
                $q->where('door_no', 'like', "%{$search}%")
                  ->orWhere('street_name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('pincode', 'like', "%{$search}%")
                  ->orWhere('state', 'like', "%{$search}%")
                  ->orWhere('landmark', 'like', "%{$search}%");
            })->orWhereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('price', 'like', "%{$search}%")
              ->orWhere('delivery_charges', 'like', "%{$search}%")
              ->orWhere('payment_mode', 'like', "%{$search}%");
        });
    }

    // Status filter functionality
    if ($request->filled('status')) {
        $status = $request->input('status');
        $query->where('status', $status);
    }

    // Default sorting: Show pending orders first, then others, sorted by latest date
    $query->orderByRaw('CASE WHEN status = 0 THEN 0 ELSE 1 END, created_at DESC');

    // Paginate the results
    $orders = $query->paginate(10);

    // Check if the request is AJAX
    if ($request->wantsJson()) {
        return response()->json($orders);
    }

    $users = Users::all(); // Fetch all users for the filter dropdown
    $products = Products::all(); // Fetch all products for the filter dropdown

    return view('orders.index', compact('users', 'orders', 'products'));
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
    public function edit(Orders $order)
    {
        // Fetch the address associated with the order
        $addresses = Addresses::find($order->address_id);
    
        if (!$addresses) {
            return redirect()->route('orders.index')->with('error', 'Address not found.');
        }
    
        return view('orders.edit', compact('order', 'addresses'));
    }
    
    
    public function update(Request $request, Orders $order)
    {
        // Validate the input
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile' => 'required|numeric',
            'alternate_mobile' => 'nullable|numeric',
            'door_no' => 'required|string|max:255',
            'street_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'pincode' => 'required|numeric',
            'state' => 'required|string|max:255',
            'landmark' => 'nullable|string|max:255',
        ]);
    
        // Update the Address
        $addresses = Addresses::find($request->input('address_id'));
        if (!$addresses) {
            return redirect()->route('orders.index')->with('error', 'Address not found.');
        }
    
        $addresses->update([
            'door_no' => $request->input('door_no'),
            'street_name' => $request->input('street_name'),
            'city' => $request->input('city'),
            'pincode' => $request->input('pincode'),
            'state' => $request->input('state'),
            'landmark' => $request->input('landmark'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'mobile' => $request->input('mobile'),
            'alternate_mobile' => $request->input('alternate_mobile'),
        ]);
    
        return redirect()->route('orders.index')->with('success', 'Order and Address updated successfully.');
    }
    

    public function getUserAddresses($userId)
    {
        // Fetch addresses associated with the user
        $addresses = Addresses::where('user_id', $userId)->get(['id', 'name']);
    
        // Return the addresses as a JSON response
        return response()->json($addresses);
    }
    
}
