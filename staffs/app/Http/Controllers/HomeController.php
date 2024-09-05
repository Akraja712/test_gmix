<?php

namespace App\Http\Controllers;

use App\Models\Points;
use App\Models\Users;
use App\Models\Trips;
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
       
        
        // Optional: Count of pending profiles and cover images
        // $pending_profile_count = Users::where('profile_verified', 0)->whereNotNull('profile')->count();
        // $pending_cover_image_count = Users::where('profile_verified', 0)->whereNotNull('cover_img')->count();
        
        return view('home', [
            
        ]);
    }
}
