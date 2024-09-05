<?php

namespace App\Http\Controllers;

use App\Http\Requests\SlideStoreRequest;
use App\Models\Slides;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SlidesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->wantsJson()) {
            return response(
                Slides::all()
            );
        }
        $slides = Slides::latest()->paginate(10);
        return view('slides.index')->with('slides', $slides);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('slides.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SlideStoreRequest $request)
    {
        $imagePath = $request->file('image')->store('slides', 'public');

        $slide = Slides::create([
            'image' => basename($imagePath),
        ]);

        if (!$slide) {
            return redirect()->back()->with('error', 'Sorry, Something went wrong while creating shop.');
        }
        return redirect()->route('slides.index')->with('success', 'Success, New Slide has been added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shops  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Slides $slides)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Slides  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Slides $slide)
    {
        return view('slides.edit', compact('slide'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shops  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slides $slide)
    {

        if ($request->hasFile('image')) {
            $newImagePath = $request->file('image')->store('slides', 'public');
            // Delete old image if it exists
            Storage::disk('public')->delete('slides/' . $slide->image);
            $slide->image = basename($newImagePath);
        }

        if (!$slide->save()) {
            return redirect()->back()->with('error', 'Sorry, Something went wrong while updating the customer.');
        }
        return redirect()->route('slides.index')->with('success', 'Success, The Slide has been updated.');
    }

    public function destroy(Slides $slide)
    {
        if (Storage::disk('public')->exists('slides/' . $slide->image)) {
            Storage::disk('public')->delete('slides/' . $slide->image);
        }
        $slide->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
