<?php

namespace App\Http\Controllers;

use App\Models\Points;
use App\Models\Users;
use App\Models\Orders;
use App\Models\Verifications;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $startOfDay = Carbon::today(); // Start of the day (00:00:00)
        $endOfDay = Carbon::today()->setTime(23, 59, 59); // End of the day (23:59:59)
        $today = Carbon::today()->format('Y-m-d');

      
        $today_customers = Users::whereDate('created_at', $today)
        ->count();

        $today_orders = Orders::whereDate('created_at', $today)
        ->count();
  
        
        // Optional: Count of pending profiles and cover images
        // $pending_profile_count = Users::where('profile_verified', 0)->whereNotNull('profile')->count();
        // $pending_cover_image_count = Users::where('profile_verified', 0)->whereNotNull('cover_img')->count();
        
        return view('home', [
            'today_customers' => $today_customers,
            'today_orders' => $today_orders,
           
        ]);
    }
}