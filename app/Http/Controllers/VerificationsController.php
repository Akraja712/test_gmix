<?php
namespace App\Http\Controllers;

use App\Models\Verifications;
use App\Models\Users;
use App\Models\Plans;
use Illuminate\Http\Request;
use Berkayk\OneSignal\OneSignalClient;
use Illuminate\Support\Facades\Storage; // Add this at the top of the file

class VerificationsController extends Controller
{
    protected $oneSignalClient;

    public function __construct(OneSignalClient $oneSignalClient)
    {
        $this->oneSignalClient = $oneSignalClient;
    }

    public function verify(Request $request)
    {
        $verificationIds = $request->input('verification_ids', []);
        
        foreach ($verificationIds as $verificationId) {
            $verification = Verifications::find($verificationId);
            if ($verification) {
                $user = Users::find($verification->user_id);
                if ($user && $user->verify_bonus_sent !== 1) {
                    $plan = Plans::find($verification->plan_id);
                    if ($plan) {
                        $validity = $plan->validity;

                        // Calculate new verification end date
                        $currentEndDate = $user->verification_end_date ? new \Carbon\Carbon($user->verification_end_date) : now();
                        $newEndDate = $currentEndDate->addDays($validity);

                        // Update user’s verification_end_date
                        $user->verification_end_date = $newEndDate->format('Y-m-d');
                        $user->save();

                        $user->verified = 1;
                        $user->save();
                    }
                }

                  // Send notification to the user who posted the verification
                  $this->sendNotificationToUser(strval($user->id));

                  $user->verified = 1;
                  $user->save();
                  
                // Update verification status
                $verification->status = 1;
                $verification->payment_status = 1;
                $verification->save();
            }
        }

        return response()->json(['success' => true]);
    }

    protected function sendNotificationToUser($user_id)
    {
        $message = "Your Profile Verified Successfully";
        $this->oneSignalClient->sendNotificationToExternalUser(
            $message,
            $user_id,
            $url = null,
            $data = null,
            $buttons = null,
            $schedule = null
        );
    }

    public function index(Request $request)
    {
        $query = Verifications::query()->with('user')->with('plan'); // Eager load the user and plan relationships
    
        // Handle the search input
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%$search%");
                  });
            });
        }
    
        // Filter by verified status with default to 0
        $status = $request->input('status', 0); // Default to 0 if not provided
        $query->where('status', $status);

        $payment_status = $request->input('payment_status', 0); // Default to 0 if not provided
        $query->where('payment_status', $payment_status);
    
        // Check if the request is AJAX
        if ($request->wantsJson()) {
            return response($query->get());
        }
    
        $verifications = $query->latest()->paginate(10); // Paginate the results
    
        $users = Users::all(); // Fetch all users for the filter dropdown
        $plans = Plans::all(); // Fetch all plans for the filter dropdown
    
        return view('verifications.index', compact('verifications', 'users', 'plans')); // Pass verifications, users, and plans to the view
    }
    public function edit(Verifications $verifications)
    {
        $users = Users::all(); // Fetch all users
        $plans = Plans::all(); // Fetch all plans
        
        return view('verifications.edit', compact('verifications', 'users', 'plans'));
    }    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Verifications  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Verifications $verifications)
{
    // Validate the request data
    $request->validate([
        'status' => 'required|integer|in:0,1,2',
        'payment_status' => 'required|integer|in:0,1,2',
    ]);

    // Update the verification model with the provided data
    $verifications->status = $request->input('status');
    $verifications->payment_status = $request->input('payment_status');

    // Save the model
    if ($verifications->save()) {
        return redirect()->route('verifications.edit', $verifications->id)
            ->with('success', 'Success, verifications have been updated.');
    } else {
        return redirect()->back()
            ->with('error', 'Sorry, something went wrong while updating the verification.');
    }
}


    public function destroy(Verifications $verification)
    {
        $verification->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    public function deleteImage(Verifications $verification)
    {
        // Check if the payment_image exists
        if (Storage::disk('public')->exists('verification/' . $verification->payment_image)) {
            // Delete the payment_image from storage
            Storage::disk('public')->delete('verification/' . $verification->payment_image);
    
            // Set the payment_image field to null in the database
            $verification->payment_image = null;
            $verification->save();
    
            return response()->json(['success' => true, 'message' => 'Payment image deleted successfully.']);
        }
    
        return response()->json(['success' => false, 'message' => 'Image not found or already deleted.'], 404);
    }
    
    
}

