<?php

namespace App\Http\Controllers;

use App\Models\Friends;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class FriendsController extends Controller
{
    public function index(Request $request)
    {
        $query = Friends::query()->with('user'); // Eager load the user relationship

        // Filter by user if user_id is provided
        if ($request->has('user_id')) {
            $user_id = $request->input('user_id');
            $query->where('user_id', $user_id);
        }

        $friends = $query->latest()->paginate(10); // Paginate the results

        $users = Users::all(); // Fetch all users for the filter dropdown

        return view('friends.index', compact('friends', 'users')); // Pass friends and users to the view
    }

    public function destroy(Friends $friends)
    {
        $friends->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
