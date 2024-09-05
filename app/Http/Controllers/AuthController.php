<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Users; 
use App\Models\Products; 
use App\Models\Addresses; 
use App\Models\Orders;
use App\Models\Friends; 
use App\Models\Points; 
use App\Models\Plans;
use App\Models\Notifications; 
use App\Models\Verifications; 
use App\Models\Transaction; 
use App\Models\Feedback;
use App\Models\Fakechats; 
use App\Models\Professions; 
use App\Models\RechargeTrans;
use App\Models\Reward_products;
use App\Models\VerificationTrans;
use App\Models\Appsettings; 
use App\Models\News; 
use App\Models\Reviews;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Berkayk\OneSignal\OneSignalClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;


class AuthController extends Controller
{
 
    public function login(Request $request)
    {
        // Retrieve phone number from the request
        $mobile = $request->input('mobile');

        if (empty($mobile)) {
            $response['success'] = false;
            $response['message'] = 'mobile is empty.';
            return response()->json($response, 400);
        }

        // Remove non-numeric characters from the phone number
        $mobile = preg_replace('/[^0-9]/', '', $mobile);

        // Check if the length of the phone number is not equal to 10
        if (strlen($mobile) !== 10) {
            $response['success'] = false;
            $response['message'] = "mobile number should be exactly 10 digits";
            return response()->json($response, 400);
        }


        // Check if a customer with the given phone number exists in the database
        $user = Users::where('mobile', $mobile)->first();

        // If customer not found, register the user
        if (!$user) {
            $user = new Users();
            $user->mobile = $mobile;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'registered' => true,
            'message' => 'Logged in successfully.',
            'data' => [
            'id' => $user->id,
            'name' => $user->name ?? '',
            'mobile' => $user->mobile,
            'points' => 0,
            'total_points' => 0,
            'updated_at' => Carbon::parse($user->updated_at)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::parse($user->created_at)->format('Y-m-d H:i:s'),
            ],
        ], 200);
    }

    public function otp(Request $request)
    {
        // Retrieve phone number from the request
        $mobile = $request->input('mobile');
    
        if (empty($mobile)) {
            return response()->json([
                'success' => false,
                'message' => 'Mobile number is empty.',
            ], 400);
        }
    
        // Remove non-numeric characters from the phone number
        $mobile = preg_replace('/[^0-9]/', '', $mobile);
    
        // Check if the length of the phone number is not exactly 10
        if (strlen($mobile) !== 10) {
            return response()->json([
                'success' => false,
                'message' => 'Mobile number should be exactly 10 digits.',
            ], 400);
        }
    
        // Generate a random 6-digit OTP
        $randomNumber = mt_rand(100000, 999999);
        $datetime = now();
    
        // Check if the mobile number already exists in the otp table
        $otpRecord = DB::table('otp')->where('mobile', $mobile)->first();
    
        if ($otpRecord) {
            // If exists, update the OTP and datetime
            DB::table('otp')->where('mobile', $mobile)->update([
                'otp' => $randomNumber,
                'datetime' => $datetime,
            ]);
        } else {
            // If not exists, insert a new record
            DB::table('otp')->insert([
                'mobile' => $mobile,
                'otp' => $randomNumber,
                'datetime' => $datetime,
            ]);
        }
    
        // Fetch the updated or newly inserted record
        $otpRecord = DB::table('otp')->where('mobile', $mobile)->first();
    
        return response()->json([
            'success' => true,
            'message' => 'OTP received successfully.',
            'data' => $otpRecord,
        ], 200);
    }
    public function userdetails(Request $request)
    {
        $user_id = $request->input('user_id');

        // Retrieve the user details for the given user_id
        $user = Users::find($user_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        $userDetails = [[
            'id' => $user->id,
            'name' => $user->name,
            'mobile' => $user->mobile,
            'points' => $user->points ?? '',
            'total_points' => $user->total_points ?? '',
            'updated_at' => Carbon::parse($user->updated_at)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::parse($user->created_at)->format('Y-m-d H:i:s'),
        ]];

        return response()->json([
            'success' => true,
            'message' => 'User details retrieved successfully.',
            'data' => $userDetails,
        ], 200);
    }
    public function product_list(Request $request)
    {
        $products = Products::orderBy('price', 'desc')->get();

        if ($products->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No products found.',
            ], 404);
        }

        $productsDetails = [];


        foreach ($products as $product) {

            $imageUrl = $product->image ? asset('storage/app/public/products/' . $product->image) : '';
            $productsDetails[] = [
                'id' => $product->id,
                'name' => $product->name,
                'unit' => $product->unit,
                'measurement' => $product->measurement,
                'quantity' => $product->quantity,
                'price' => (string) $product->price,
                'image' => $imageUrl,
                'updated_at' => Carbon::parse($product->updated_at)->format('Y-m-d H:i:s'),
                'created_at' => Carbon::parse($product->created_at)->format('Y-m-d H:i:s'),
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'products Details retrieved successfully.',
            'data' => $productsDetails,
        ], 200);
    }
    public function add_address(Request $request)
    {
        $user_id = $request->input('user_id'); 
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $mobile = $request->input('mobile');
        $alternate_mobile = $request->input('alternate_mobile');
        $door_no = $request->input('door_no');
        $street_name = $request->input('street_name');
        $city = $request->input('city');
        $pincode = $request->input('pincode');
        $state = $request->input('state');
        $landmark = $request->input('landmark');

        // Validate input fields
        if (empty($user_id)) {
            return response()->json([
                'success' => false,
                'message' => 'user_id is empty.',
            ], 400);
        }

        if (empty($first_name)) {
            return response()->json([
                'success' => false,
                'message' => 'First name is empty.',
            ], 400);
        }

        if (empty($mobile)) {
            return response()->json([
                'success' => false,
                'message' => 'mobile is empty.',
            ], 400);
        }

        if (empty($alternate_mobile)) {
            return response()->json([
                'success' => false,
                'message' => 'Alternate Mobile is empty.',
            ], 400);
        }

        if ($mobile === $alternate_mobile) {
            return response()->json([
                'success' => false,
                'message' => 'Mobile and Alternate Mobile cannot be the same.',
            ], 400);
        }

        if (empty($door_no)) {
            return response()->json([
                'success' => false,
                'message' => 'door_no is empty.',
            ], 400);
        }

        if (empty($street_name)) {
            return response()->json([
                'success' => false,
                'message' => 'street_name is empty.',
            ], 400);
        }

        if (empty($city)) {
            return response()->json([
                'success' => false,
                'message' => 'city is empty.',
            ], 400);
        }

        if (empty($pincode)) {
            return response()->json([
                'success' => false,
                'message' => 'pincode is empty.',
            ], 400);
        }

        if (empty($state)) {
            return response()->json([
                'success' => false,
                'message' => 'state is empty.',
            ], 400);
        }

        // Check if user exists
        $user = Users::find($user_id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'user not found.',
            ], 404);
        }

        // Check if an address already exists for this user_id
        $address = Addresses::where('user_id', $user_id)->first();

        if ($address) {
            // Update the existing address
            $address->first_name = $first_name;
            $address->last_name = $last_name ?? $first_name;
            $address->mobile = $mobile;
            $address->alternate_mobile = $alternate_mobile;
            $address->door_no = $door_no;
            $address->street_name = $street_name;
            $address->city = $city;
            $address->pincode = $pincode;
            $address->state = $state;
            $address->landmark = $landmark;
        } else {
            // Create a new Address instance
            $address = new Addresses();
            $address->user_id = $user_id; 
            $address->first_name = $first_name;
            $address->last_name = $last_name ?? $first_name;
            $address->mobile = $mobile;
            $address->alternate_mobile = $alternate_mobile;
            $address->door_no = $door_no;
            $address->street_name = $street_name;
            $address->city = $city;
            $address->pincode = $pincode;
            $address->state = $state;
            $address->landmark = $landmark;
        }

        // Save the address
        if (!$address->save()) {
            return response()->json([
            'success' => false,
            'message' => 'Failed to save address.',
            ], 500);
        }

        $addressDetails = [
            'id' => $address->id,
            'user_id' => $address->user_id,
            'first_name' => $address->first_name,
            'last_name' => $address->last_name ?? '',
            'mobile' => $address->mobile,
            'alternate_mobile' => $address->alternate_mobile,
            'door_no' => $address->door_no,
            'street_name' => $address->street_name,
            'city' => $address->city,
            'pincode' => $address->pincode,
            'state' => $address->state,
            'landmark' => $address->landmark ?? '',
            'updated_at' => Carbon::parse($address->updated_at)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::parse($address->created_at)->format('Y-m-d H:i:s'),
        ];

        if ($address->wasRecentlyCreated) {
            return response()->json([
            'success' => true,
            'message' => 'Address added successfully.',
            'data' => $addressDetails,
            ], 200);
        } else {
            return response()->json([
            'success' => true,
            'message' => 'Address updated successfully.',
            'data' => $addressDetails,
            ], 200);
        }
    }
     public function address_list(Request $request)
    {
        $address_id = $request->input('address_id');

        if (empty($address_id)) {
            return response()->json([
                'success' => false,
                'message' => 'address_id is empty.',
            ], 400);
        }


        // Retrieve the user details for the given user_id
        $address = addresses::find($address_id);

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'address not found.',
            ], 200);
        }

        $addressDetails = [[
            'id' => $address->id,
            'user_id' => $address->user_id,
            'name' => $address->name,
            'mobile' => $address->mobile,
            'alternate_mobile' => $address->alternate_mobile,
            'door_no' => $address->door_no,
            'street_name' => $address->street_name,
            'city' => $address->city,
            'pincode' => $address->pincode,
            'state' => $address->state,
             'landmark' => $address->landmark ?? '',
            'updated_at' => Carbon::parse($address->updated_at)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::parse($address->created_at)->format('Y-m-d H:i:s'),
        ]];

        return response()->json([
            'success' => true,
            'message' => 'Address details retrieved successfully.',
            'data' => $addressDetails,
        ], 200);
    }

    public function createOrder()
        {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjUwOTY4OTAsInNvdXJjZSI6InNyLWF1dGgtaW50IiwiZXhwIjoxNzI1OTIwMjcyLCJqdGkiOiJ6VFFtdjV4RWRrNE1IbTdLIiwiaWF0IjoxNzI1MDU2MjcyLCJpc3MiOiJodHRwczovL3NyLWF1dGguc2hpcHJvY2tldC5pbi9hdXRob3JpemUvdXNlciIsIm5iZiI6MTcyNTA1NjI3MiwiY2lkIjoyNzI4MzUyLCJ0YyI6MzYwLCJ2ZXJib3NlIjpmYWxzZSwidmVuZG9yX2lkIjowLCJ2ZW5kb3JfY29kZSI6IiJ9.sLpaoPK_vihXBiFO6ivYzXk6WX9-iORL28RYzz8UPxY'
            ])->post('https://apiv2.shiprocket.in/v1/external/orders/create/adhoc', [
                "order_id" => "224-447",
                "order_date" => "2024-08-31 01:11",
                "pickup_location" => "Trichy",
                "channel_id" => "",
                "comment" => "Reseller: M/s Goku",
                "billing_customer_name" => "Naruto",
                "billing_last_name" => "Uzumaki",
                "billing_address" => "House 221B, Leaf Village",
                "billing_address_2" => "Near Hokage House",
                "billing_city" => "New Delhi",
                "billing_pincode" => "110002",
                "billing_state" => "Delhi",
                "billing_country" => "India",
                "billing_email" => "naruto@uzumaki.com",
                "billing_phone" => "9876543210",
                "shipping_is_billing" => true,
                "order_items" => [
                    [
                        "name" => "Kunai",
                        "sku" => "chakra123",
                        "units" => 10,
                        "selling_price" => "900",
                        "discount" => "",
                        "tax" => "",
                        "hsn" => 441122
                    ]
                ],
                "payment_method" => "Prepaid",
                "shipping_charges" => 0,
                "giftwrap_charges" => 0,
                "transaction_charges" => 0,
                "total_discount" => 0,
                "sub_total" => 9000,
                "length" => 10,
                "breadth" => 15,
                "height" => 20,
                "weight" => 2.5
            ]);

            if ($response->successful()) {
                return response()->json(['message' => 'Order created successfully!', 'data' => $response->json()]);
            } else {
                return response()->json(['message' => 'Order creation failed!', 'error' => $response->json()], $response->status());
            }
        }

        public function place_order(Request $request)
        {
            $user_id = $request->input('user_id');
            $product_id = $request->input('product_id');
            $address_id = $request->input('address_id');
            $payment_mode = $request->input('payment_mode');
        
            if (empty($user_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'user_id is empty.',
                ], 400);
            }
        
            if (empty($product_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'product_id is empty.',
                ], 400);
            }
        
            if (empty($address_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'address_id is empty.',
                ], 400);
            }
        
            if (empty($payment_mode)) {
                return response()->json([
                    'success' => false,
                    'message' => 'payment_mode is empty.',
                ], 400);
            }
        
            // Check if user exists
            $user = Users::find($user_id);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'user not found.',
                ], 404);
            }
        
            // Check if product exists and get its price
            $product = Products::find($product_id);
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'product not found.',
                ], 404);
            }
            $price = $product->price;
        
            // Check if address exists
            $address = Addresses::find($address_id);
            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'address not found.',
                ], 404);
            }
        
            // Get delivery charges from settings table but i getting from news table some issue of the settings name so i change name into news
            $delivery_charges = 0; // Default to 0 for prepaid
            if ($payment_mode === 'COD') {
                $delivery_charges = News::value('delivery_charges');
            }
        
            // Check if the order already exists
            $latestOrderId = Orders::where('user_id', $user_id)
            ->latest('created_at')
            ->value('id');
        

        
            // Check if payment mode is valid
            if ($payment_mode !== 'Prepaid' && $payment_mode !== 'COD') {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid payment mode. Payment mode should be either Prepaid or COD.',
                ], 400);
            }
            $shipping_charges = 60 ;

            if($payment_mode == 'Prepaid'){
                $payment_mode = 'Prepaid';
                $total_price = $price + 0;
            }else{
                $payment_mode = 'COD';
                $total_price = $price + $delivery_charges;
             }
        
            $order = new Orders();
            $order->user_id = $user_id;
            $order->product_id = $product_id;
            $order->address_id = $address_id;
            $order->price = $price;
            $order->delivery_charges = $delivery_charges;
            $order->payment_mode = $payment_mode;
            $order->total_price = $total_price;
            $order->live_tracking = 'https://gmix.shiprocket.co/tracking/'; 
            $order->ordered_date = Carbon::now();
            $order->save();

                    // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully.',
                'order_id' => $order->id,
                'total_price' => $total_price,
            ], 200);
        }

        public function ship_webhook(Request $request)
        {
            
            // Get the raw POST data
            $data = $request->getContent();

            // Decode the JSON data
            $dataArray = json_decode($data, true);
            

            // Perform actions based on the webhook event
            if ($dataArray && isset($dataArray['order_id'])) {
                
                $order_id = $dataArray['order_id'];

                
                $order_id = str_replace("Gmix-", "", $order_id);
         

                $shipment_status = $dataArray['shipment_status'];
                $awb = $dataArray['awb'];
                $etd = Carbon::parse($dataArray['etd'])->format('Y-m-d');

                DB::table('orders')->where('id', $order_id)->update([
                    'shipment_status' => $shipment_status,
                    'awb' => $awb,
                    'est_delivery_date' => $etd,
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'success',
                ], 200);

                // Log the order_id for debugging purposes
                //Log::info('Order ID received:', ['order_id' => $order_id]);

                // Handle other events or actions based on order_id if needed
            } else {
                // Log an error if the order_id is not present or data is invalid
                Log::error('Invalid data received:', ['data' => $dataArray]);
                return response()->json([
                    'success' => true,
                    'message' => 'success',
                ], 200);
            }

            // Respond with a 200 status code to acknowledge receipt of the webhook
            
        }

        
        public function orders_list(Request $request)
        {
            $user_id = $request->input('user_id');

            if (empty($user_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'user_id is empty.',
                ], 400);
            }
        
            // Retrieve the orders for the given user
            $orders = Orders::where('user_id', $user_id)
                ->latest('created_at')
                ->get();
        
            if ($orders->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No orders found for the user.',
                ], 404);
            }
        
            $ordersDetails = [];
        
            foreach ($orders as $order) {
        
                if ($order->user_id == $user_id) {
                    $user = Users::find($order->user_id);
                    $product = Products::find($order->product_id);
                    $address = Addresses::find($order->address_id);
        
                    // Determine the status label
                    $statusLabel = $order->status == 0 ? 'Wait For Confirmation' : ($order->status == 1 ? 'Confirmed' : ($order->status == 2 ? 'Cancelled' : ($order->status == 3 ? 'Shipped' : ($order->status == 4 ? 'Delivered' : (string) $order->status))));

                    $statusColor = $order->status == 0 ? '#01579B' : ($order->status == 1 ? '#006064' : ($order->status == 2 ? '#DD2C00' : ($order->status == 3 ? '#01579B' : ($order->status == 4 ? '#1B5E20' : '#0D47A1'))));

                    $ordersDetails[] = [
                        'id' => $order->id,
                        'user_id' => $order->user_id,
                        'user_name' => $user->name ?? '',
                        'user_mobile_number' => $user->mobile ?? '',
                        'first_name' => $address->first_name, 
                        'last_name' => $address->last_name ?? '',
                        'product_name' => $product->name,
                        'unit' => $product->unit,
                        'measurement' => $product->measurement,
                        'quantity' => $product->quantity,
                        'delivery_charges' => $order->delivery_charges,
                        'payment_mode' => $order->payment_mode,
                        'price' => (string) $order->price,
                        'total_price' => (string) $order->total_price,
                        'status' => $statusLabel, // Use status label
                        'status_color' => $statusColor,
                        'live_tracking' => $order->live_tracking . $order->awb,
                        'ship_rocket' => $order->ship_rocket ?? '',
                        'est_delivery_date' => $order->est_delivery_date ?? '',
                        'ordered_date' => Carbon::parse($order->ordered_date)->format('Y-m-d'),
                        'updated_at' => Carbon::parse($order->updated_at)->format('Y-m-d H:i:s'),
                        'created_at' => Carbon::parse($order->created_at)->format('Y-m-d H:i:s'),
                    ];
                }
            }
        
            return response()->json([
                'success' => true,
                'message' => 'Orders retrieved successfully.',
                'data' => $ordersDetails,
            ], 200);
        }
        

    public function my_address_list(Request $request)
    {
        $user_id = $request->input('user_id');

        // Retrieve the orders for the given user
        $addresses = Addresses::where('user_id', $user_id)->get();

        if ($addresses->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No addresses found for the user.',
            ], 200);
        }

        $addressesDetails = [];

        foreach ($addresses as $address) {

            if ($address->user_id == $user_id) {
                $user = Users::find($address->user_id);
                $addressesDetails[] = [
                    'id' => $address->id,
                    'user_id' => $address->user_id,
                    'user_name' => $user->name,
                    'first_name' => $address->first_name,
                    'last_name' => $address->last_name ?? '',
                    'mobile' => $address->mobile,
                    'alternate_mobile' => $address->alternate_mobile,
                    'door_no' => $address->door_no,
                    'street_name' => $address->street_name,
                    'city' => $address->city,
                    'pincode' => $address->pincode,
                    'state' => $address->state,
                     'landmark' => $address->landmark ?? '',
                    'updated_at' => Carbon::parse($address->updated_at)->format('Y-m-d H:i:s'),
                    'created_at' => Carbon::parse($address->created_at)->format('Y-m-d H:i:s'),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Addresses retrieved successfully.',
            'data' => $addressesDetails,
        ], 200);
    }

    public function settings_list(Request $request)
{
    // Retrieve all news settings
    $news = News::all();

    if ($news->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No settings found.',
        ], 404);
    }

    $newsData = [];
    foreach ($news as $item) {
        $newsData[] = [
            'id' => $item->id,
            'delivery_charges' => $item->delivery_charges,
            'customer_support_number' => $item->customer_support_number,
        ];
    }

    return response()->json([
        'success' => true,
        'message' => 'Settings listed successfully.',
        'data' => $newsData,
    ], 200);
}

public function reward_product_list(Request $request)
{
    // Retrieve all news settings
    $reward_products = Reward_products::all();

    if ($reward_products->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No reward product found.',
        ], 404);
    }

    $reward_productData = [];
    foreach ($reward_products as $reward_product) {
        $imageUrl = $reward_product->image ? asset('storage/app/public/reward_products/' . $reward_product->image) : '';
        $reward_productsData[] = [
            'id' => $reward_product->id,
            'name' => $reward_product->name,
            'points' => (string) $reward_product->points,
            'description' => $reward_product->description,
            'image' => $imageUrl,
            'updated_at' => Carbon::parse($reward_product->updated_at)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::parse($reward_product->created_at)->format('Y-m-d H:i:s'),
        ];
    }

    return response()->json([
        'success' => true,
        'message' => 'Reward Products listed successfully.',
        'data' => $reward_productsData,
    ], 200);
}

public function pincode(Request $request)
{
    $pincode = $request->input('pincode');

    // Validate that the pincode is provided
    if (empty($pincode)) {
        return response()->json([
            'success' => false,
            'message' => 'Pincode is empty.',
        ], 400);
    }

    // Make a GET request to the external API
    $response = Http::get("http://www.postalpincode.in/api/pincode/{$pincode}");

    // Check if the request was successful
    if ($response->successful()) {
        // Decode the JSON response to get the address details
        $pincodeDetails = $response->json();

        return response()->json([
            'success' => true,
            'message' => 'Pincode retrieved successfully.',
            'data' => $pincodeDetails,
        ], 200);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve address details. Please try again later.',
        ], 500);
    }
}

public function appsettings_list(Request $request)
{
    // Retrieve all news settings
    $appsettings = Appsettings::all();

    if ($appsettings->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No Appsettings found.',
        ], 404);
    }

    $appsettingsData = [];
    foreach ($appsettings as $item) {
        $appsettingsData[] = [
            'id' => $item->id,
            'link' => $item->link,
            'app_version' => $item->app_version,
            'description' => $item->description,
        ];
    }

    return response()->json([
        'success' => true,
        'message' => 'App Settings listed successfully.',
        'data' => $appsettingsData,
    ], 200);
}

public function reviews_list(Request $request)
{
    $product_id = $request->input('product_id');

    // Validate that the product_id is provided
    if (empty($product_id)) {
        return response()->json([
            'success' => false,
            'message' => 'product_id is empty.',
        ], 400);
    }

    // Retrieve reviews for the given product_id
    $reviews = Reviews::where('product_id', $product_id)->get();

    if ($reviews->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No reviews found for the product.',
        ], 404);
    }

    $reviewsData = [];
    foreach ($reviews as $item) {
        $reviewsData[] = [
            'id' => $item->id,
            'product_id' => $item->product_id,
            'description' => $item->description,
            'image1' => $item->image1,
            'image2' => $item->image2,
            'image3' => $item->image3,
        ];
    }

    return response()->json([
        'success' => true,
        'message' => 'Reviews listed successfully.',
        'data' => $reviewsData,
    ], 200);
}
}