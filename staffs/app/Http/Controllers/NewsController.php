<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function edit()
    {
        // Assuming you are editing the News with ID 1
        $news = News::findOrFail(1);
        return view('news.edit', compact('news'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'telegram' => 'required|string|max:255',
            'instagram' => 'required|string|max:255',
            'upi_id' => 'required|string|max:255',
            'privacy_policy' => 'required|string',
            'terms_conditions' => 'required|string',
        ]);

        $news = News::findOrFail(1); // Again, assuming ID 1 for simplicity
        $news->telegram = $request->input('telegram');
        $news->instagram = $request->input('instagram');
        $news->upi_id = $request->input('upi_id');
        $news->privacy_policy = $request->input('privacy_policy');
        $news->terms_conditions = $request->input('terms_conditions');
    

        if ($news->save()) {
            return redirect()->route('news.edit')->with('success', 'Success, Settings has been updated.');
        } else {
            return redirect()->route('news.edit')->with('error', 'Sorry, something went wrong while updating the News.');
        }
    }
}



