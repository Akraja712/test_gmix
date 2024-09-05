<?php
namespace App\Http\Controllers;

use App\Models\Transactions;
use App\Models\Users;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        $query = Transactions::query()->with('user'); // Eager load the user relationship

        // Filter by user if user_id is provided
        if ($request->has('user_id')) {
            $user_id = $request->input('user_id');
            $query->where('user_id', $user_id);
        }

        // Fetch distinct types
        $types = Transactions::select('type')->distinct()->get();

        // Filter by type if it exists in the request
        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        // Filter by search term if it exists in the request
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($query) use ($search) {
                $query->where('id', 'like', "%{$search}%")
                      ->orWhere('type', 'like', "%{$search}%")
                      ->orWhere('points', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($query) use ($search) {
                          $query->where('name', 'like', "%{$search}%");
                      });
            });
        }

        // Paginate the results
        $transactions = $query->latest()->paginate(10);

        // Append query parameters to the pagination links
        $transactions->appends($request->except('page'));

        // Fetch all users for the filter dropdown
        $users = Users::all();

        // Pass transactions, users, and types to the view
        return view('transactions.index', compact('transactions', 'users', 'types'));
    }

    public function destroy(Transactions $transactions)
    {
        $transactions->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
