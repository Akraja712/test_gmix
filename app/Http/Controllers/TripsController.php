<?php

namespace App\Http\Controllers;

use App\Http\Requests\TripsStoreRequest;
use App\Models\Trips;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Berkayk\OneSignal\OneSignalClient;

class TripsController extends Controller
{
    protected $oneSignalClient;

    public function __construct(OneSignalClient $oneSignalClient)
    {
        $this->oneSignalClient = $oneSignalClient;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function updateStatus(Request $request)
     {
         $tripIds = $request->input('trip_ids', []);
         $status = $request->input('status');
 
         foreach ($tripIds as $tripId) {
             $trip = Trips::find($tripId);
             if ($trip) {
                 $oldStatus = $trip->trip_status;
                 $trip->trip_status = $status;
                 $trip->trip_datetime = now();
                 $trip->save();
 
                 // Only send notifications if the status has changed
                 if ($oldStatus !== $status) {
                     if ($status == 1) {
                        $userId = $trip->user_id;

                         $this->sendNotificationToAllUsers($trip->user_id);
 
                         // Send notification to the user who posted the trip
                         $this->sendNotificationToUser(strval($userId));
                     }
                 }
             }
         }
 
         return response()->json(['success' => true]);
     }
 
     /**
      * Send notification to all users that a new trip has been posted.
      *
      * @param int $userId
      * @return void
      */
     protected function sendNotificationToAllUsers($userId)
     {
         $user = Users::find($userId);
         if ($user) {
             $message = $user->name . " posted a new trip";
             $this->oneSignalClient->sendNotificationToAll(
                 $message,
                 $url = null,
                 $data = null,
                 $buttons = null,
                 $schedule = null
             );
         }
     }
 
     /**
      * Send notification to the user that their trip has been approved.
      *
      * @param int $userId
      * @return void
      */
     protected function sendNotificationToUser($userId)
     {
             $message = "Your trip has been approved successfully";
             $this->oneSignalClient->sendNotificationToExternalUser(
                 $message,
                 $userId,
                 $url = null,
                 $data = null,
                 $buttons = null,
                 $schedule = null
             );
     }
     public function index(Request $request)
     {
        $query = Trips::query()->with('users');

        // Handle the search input
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('trip_title', 'like', "%$search%")
                  ->orWhere('trip_type', 'like', "%$search%")
                  ->orWhere('location', 'like', "%$search%")
                  ->orWhereHas('users', function ($q) use ($search) {
                      $q->where('name', 'like', "%$search%");
                  });
            });
        }
    
// Filter by verified status
if ($request->filled('trip_status')) {
    $trip_status = $request->input('trip_status');
    $query->where('trip_status', $trip_status);
}

// Default sorting: Show pending trips first, then other statuses
$query->orderByRaw('CASE WHEN trip_status = 0 THEN 1 ELSE 2 END')
      ->latest(); // This will sort by trip_datetime in descending order, after showing pending trips first

        // Check if the request is AJAX
        if ($request->wantsJson()) {
            return response($query->get());
        }
    
        $trips = $query->latest()->paginate(10);
        $users = Users::all();
     
         return view('trips.index', compact('trips', 'users'));
     }
     
     
     
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = Users::all(); // Fetch all trips
        return view('trips.create', compact('users')); // Pass trips to the view
    }
  


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  
    
    public function store(TripsStoreRequest $request)
    {

          // Validate if the user already has a pending trip
            $pendingTrip = Trips::where('user_id', $request->user_id)
            ->where('trip_status', 0) // Assuming 0 means pending
            ->exists();

        if ($pendingTrip) {
        return redirect()->back()->with('error', 'You already have a pending trip. Please wait until it is approved before adding a new one.');
        }
    
        // Check if a file has been uploaded
   if ($request->hasFile('trip_image')) {
    $imageName = $request->file('trip_image')->getClientOriginalName(); // Get the original file name
    $imagePath = $request->file('trip_image')->storeAs('trips', $imageName);
} else {
    // Handle the case where no file has been uploaded
    $imagePath = null; // or provide a default image path
}
        $trips = Trips::create([
            'trip_type' => $request->trip_type,
            'location' => $request->location,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'trip_title' => $request->trip_title,
            'trip_description' => $request->trip_description,
            'user_id' => $request->user_id,
            'trip_image' => $imageName, // Save only the image name in the database
            'trip_datetime' => now(),
            
        ]);
    
        if (!$trips) {
            return redirect()->back()->with('error', 'Sorry, Something went wrong while creating user.');
        }
    
        return redirect()->route('trips.index')->with('success', 'Success, New trips has been added successfully!');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Trips  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Trips $trips)
    {

    }

    public function user()
    {
        return $this->belongsTo(users::class);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Trips $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Trips $trips)
    {
        $users = Users::all(); // Fetch all shops
        return view('trips.edit', compact('trips', 'users'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Trips  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trips $trips)

    {

        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'trip_type' => 'required|string',
            'location' => 'required|string',
            'trip_title' => 'required|string',
            'trip_description' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);
        
        $trips->trip_type = $request->trip_type;
        $trips->location = $request->location;
        $trips->from_date = $request->from_date;
        $trips->to_date = $request->to_date;
        $trips->trip_title = $request->trip_title;
        $trips->trip_description = $request->trip_description;
        $trips->user_id = $request->user_id;
        $trips->trip_status = $request->trip_status;
        $trips->trip_datetime = now(); 

        if ($request->hasFile('trip_image')) {
            $newImagePath = $request->file('trip_image')->store('trips', 'public');
            Storage::disk('public')->delete('trips/' . $trips->trip_image);
            $trips->trip_image = basename($newImagePath);
        }


        if (!$trips->save()) {
            return redirect()->back()->with('error', 'Sorry, Something went wrong while updating the customer.');
        }
        return redirect()->route('trips.edit', $trips->id)->with('success', 'Success, Trip has been updated.');
    }

    public function destroy(Trips $trips)
    {

         // Check if the profile image exists and delete it
         if (Storage::disk('public')->exists('trips/' . $trips->trip_image)) {
            Storage::disk('public')->delete('trips/' . $trips->trip_image);
        }
        $trips->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
