<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserNotificationsStoreRequest;
use App\Models\UserNotifications;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Berkayk\OneSignal\OneSignalClient;

class UserNotificationsController extends Controller
{
    protected $oneSignalClient;

    public function __construct(OneSignalClient $oneSignalClient)
    {
        $this->oneSignalClient = $oneSignalClient;
    }

    public function index(Request $request)
    {
        $query = UserNotifications::query();
        
        $usernotifications = $query->latest()->paginate(10);
        return view('usernotifications.index')->with('usernotifications', $usernotifications);
    }

    public function create()
    {
        return view('usernotifications.create');
    }

    public function store(UserNotificationsStoreRequest $request)
    {
        // Log the input to inspect the values
        Log::info('UserNotification request data: ', $request->all());

        // Validate and convert inputs if necessary
        $message = (string) $request->message; // Ensure the message is a string
        $title = (string) $request->title; // Ensure the message is a string

        // Log the converted values
        Log::info('Converted data: ', [
            'message' => $message,
            'title' => $title,
        ]);

        $usernotification = UserNotifications::create([
            'message' => $message,
            'title' => $title,
            'datetime' => now(),
        ]);

        if (!$usernotification) {
            return redirect()->back()->with('error', 'Something went wrong while creating the User notification.');
        }

        try {
            // Ensure the message is a string
            $response = $this->oneSignalClient->sendNotificationToAll(
                $title,
                $message,
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null
            );

            // Log the response from OneSignal
            Log::info('OneSignal response: ', ['response' => $response]);
            
            Log::info('Notification sent to all successfully');
        } catch (\Exception $e) {
            Log::error('Error sending notification: ', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error sending notification: ' . $e->getMessage());
        }

        return redirect()->route('usernotifications.index')->with('success', 'User Notification created and sent successfully.');
    }

    public function show(UserNotifications $usernotifications)
    {
        // Implement show logic if needed
    }

    public function edit(UserNotifications $usernotifications)
    {
        return view('usernotifications.edit', compact('usernotifications'));
    }

    public function update(Request $request, UserNotifications $usernotifications)
    {
        $usernotifications->message = (string) $request->message;
        $usernotifications->title = (string) $request->title;
        $usernotifications->datetime = now();

        if (!$usernotifications->save()) {
            return redirect()->back()->with('error', 'Sorry, something went wrong while updating the notification.');
        }

        return redirect()->route('usernotifications.edit', $usernotifications->id)->with('success', 'Success, User notification has been updated.');
    }

    public function destroy(UserNotifications $usernotifications)
    {
        $usernotifications->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
