<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersStoreRequest;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Users::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%$search%")
                  ->orWhere('mobile', 'like', "%$search%");
        }

        // Get perPage value from the request, default to 10
        $perPage = $request->input('perPage', 10);

        // Pagination
        $users = $query->latest()->paginate($perPage); // Show latest users first

        // Check if the request is AJAX
        if ($request->wantsJson()) {
            return response()->json($users);
        }

        $allUsers = Users::all(); // Fetch all users for the filter dropdown
        return view('users.index', compact('users', 'allUsers', 'perPage'));
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(UsersStoreRequest $request)
    {

        $users = Users::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            
        ]);

        if (!$users) {
            return redirect()->back()->with('error', 'Sorry, Something went wrong while creating profession.');
        }
        return redirect()->route('users.index')->with('success', 'Success, New user has been added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(users $users)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Users $users)
    {
        return view('users.edit', compact('users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Users $users)
    {
        $users->name = $request->name;
        $users->mobile = $request->mobile;
        $users->points = $request->points;
        $users->total_points = $request->total_points;

        if (!$users->save()) {
            return redirect()->back()->with('error', 'Sorry, Something went wrong while updating the user.');
        }
        return redirect()->route('users.edit', $users->id)->with('success', 'Success, user has been updated.');
    }
    
    public function destroy(Users $users)
    {
        $users->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
