<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationsStoreRequest;
use App\Models\Notifications;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Berkayk\OneSignal\OneSignalClient;

class NotificationsController extends Controller
{
    protected $oneSignalClient;

    public function __construct(OneSignalClient $oneSignalClient)
    {
        $this->oneSignalClient = $oneSignalClient;
    }

    public function index(Request $request)
    {
        $query = Notifications::query()->with('user');

        if ($request->has('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        $notifications = $query->latest()->paginate(10);
        $users = Users::all();

        return view('notifications.index', compact('notifications', 'users'));
    }

    public function create()
    {
        $users = Users::all();
        return view('notifications.create', compact('users'));
    }

    public function store(NotificationsStoreRequest $request)
    {
        // Log the input to inspect the values
        Log::info('Notification request data: ', $request->all());

        // Validate and convert inputs if necessary
        $message = (string) $request->message; // Ensure the message is a string
        $user_id = (int) $request->user_id; // Ensure user_id is an integer
        $notify_user_id = (int) $request->notify_user_id; // Ensure notify_user_id is an integer

        // Log the converted values
        Log::info('Converted data: ', [
            'message' => $message,
            'user_id' => $user_id,
            'notify_user_id' => $notify_user_id,
        ]);

        $notification = Notifications::create([
            'message' => $message,
            'user_id' => $user_id,
            'notify_user_id' => $notify_user_id,
            'datetime' => now(),
        ]);

        if (!$notification) {
            return redirect()->back()->with('error', 'Something went wrong while creating the notification.');
        }

        try {
            // Ensure the message is a string
            $response = $this->oneSignalClient->sendNotificationToAll(
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

        return redirect()->route('notifications.index')->with('success', 'Notification created and sent successfully.');
    }

    public function show(Notifications $notifications)
    {
        // Implement show logic if needed
    }

    public function edit(Notifications $notifications)
    {
        $users = Users::all(); 
        return view('notifications.edit', compact('notifications', 'users'));
    }

    public function update(Request $request, Notifications $notifications)
    {
        $notifications->message = (string) $request->message;
        $notifications->user_id = (int) $request->user_id;
        $notifications->notify_user_id = (int) $request->notify_user_id;
        $notifications->datetime = now();

        if (!$notifications->save()) {
            return redirect()->back()->with('error', 'Sorry, something went wrong while updating the notification.');
        }

        return redirect()->route('notifications.edit', $notifications->id)->with('success', 'Success, notification has been updated.');
    }

    public function destroy(Notifications $notifications)
    {
        $notifications->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
