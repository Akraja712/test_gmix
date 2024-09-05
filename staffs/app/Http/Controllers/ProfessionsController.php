<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfessionsStoreRequest;
use App\Models\Professions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfessionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Professions::query();
    
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('profession', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
        }
    
        if ($request->wantsJson()) {
            return response($query->get());
        }
    
        $professions = $query->latest()->paginate(10);
        return view('professions.index')->with('professions', $professions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('professions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(ProfessionsStoreRequest $request)
    {

        $professions = Professions::create([
            'profession' => $request->profession,
        ]);

        if (!$professions) {
            return redirect()->back()->with('error', 'Sorry, Something went wrong while creating profession.');
        }
        return redirect()->route('professions.index')->with('success', 'Success, New profession has been added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(professions $professions)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Professions $professions)
    {
        return view('professions.edit', compact('professions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Professions $professions)
    {
        $professions->profession = $request->profession;


        if (!$professions->save()) {
            return redirect()->back()->with('error', 'Sorry, Something went wrong while updating the profession.');
        }
        return redirect()->route('professions.edit', $professions->id)->with('success', 'Success, professions has been updated.');
    }

    public function destroy(Professions $professions)
    {
        $professions->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
