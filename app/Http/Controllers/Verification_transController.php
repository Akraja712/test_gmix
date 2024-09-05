<?php

namespace App\Http\Controllers;

use App\Models\VerificationTrans;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class Verification_transController extends Controller
{
    public function index(Request $request)
    {
        $query = VerificationTrans::query()->with('user'); // Eager load the user relationship

         // Filter by user if user_id is provided
         if ($request->has('user_id')) {
            $user_id = $request->input('user_id');
            $query->where('user_id', $user_id);
        }
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($query) use ($search) {
                $query->where('id', 'like', "%{$search}%")
                      ->orWhere('order_id', 'like', "%{$search}%")
                      ->orWhere('txn_id', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($query) use ($search) {
                          $query->where('name', 'like', "%{$search}%");
                          $query->where('email', 'like', "%{$search}%");
                      });
            });
        }
        $verification_trans = $query->latest()->paginate(10); // Paginate the results

        $users = Users::all(); // Fetch all users for the filter dropdown

        return view('verification_trans.index', compact('verification_trans', 'users')); // Pass friends and users to the view
    }

    public function destroy(VerificationTrans $verification_trans)
    {
        $verification_trans->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
