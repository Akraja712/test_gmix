<?php

namespace App\Http\Controllers;

use App\Models\Appsettings;
use Illuminate\Http\Request;

class AppsettingsController extends Controller
{
    public function edit($id)
{
    $appsettings = Appsettings::findOrFail($id);
    return view('appsettings.edit', compact('appsettings'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'link' => 'required|string|max:255',
        'app_version' => 'required|string|max:255',
        'description' => 'required|string',
        'login' => 'required|Boolean',
    ]);

    $appsettings = Appsettings::findOrFail($id);
    $appsettings->link = $request->input('link');
    $appsettings->app_version = $request->input('app_version');
    $appsettings->description = $request->input('description');
    $appsettings->login = $request->input('login');

    if ($appsettings->save()) {
        return redirect()->route('appsettings.edit', $id)->with('success', 'Success, App Settings has been updated.');
    } else {
        return redirect()->route('appsettings.edit', $id)->with('error', 'Sorry, something went wrong while updating the settings.');
    }
}

}



