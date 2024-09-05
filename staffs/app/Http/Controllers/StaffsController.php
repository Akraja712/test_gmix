<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffsStoreRequest;
use App\Models\Staffs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Staffs::query();
    
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%$search%")
                  ->orWhere('mobile', 'like', "%$search%");
        }
    
        if ($request->wantsJson()) {
            return response($query->get());
        }
    
        $staffs = $query->latest()->paginate(10);
        return view('staffs.index')->with('staffs', $staffs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('staffs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(StaffsStoreRequest $request)
    {

        $staffs = Staffs::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'password' => $request->password,
        ]);

        if (!$staffs) {
            return redirect()->back()->with('error', 'Sorry, Something went wrong while creating profession.');
        }
        return redirect()->route('staffs.index')->with('success', 'Success, New Staffs has been added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(staffs $staffs)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Staffs $staff)
    {
        return view('staffs.edit', compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Staffs $staff)
    {
        $staff->name = $request->name;
        $staff->mobile = $request->mobile;
        $staff->password = $request->password;

        if (!$staff->save()) {
            return redirect()->back()->with('error', 'Sorry, Something went wrong while updating the user.');
        }
        return redirect()->route('staffs.edit', $staff->id)->with('success', 'Success, Staffs has been updated.');
    }
    
    public function destroy(Staffs $staffs)
    {
        $staffs->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
