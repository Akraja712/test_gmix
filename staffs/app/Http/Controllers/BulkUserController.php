<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class BulkUserController extends Controller
{
    public function create()
    {
        return view('bulk-users.upload'); // Assuming your view file is named 'upload.blade.php'
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        
        // Use the UsersImport class to handle the import
        Excel::import(new UsersImport, $file);

        return redirect()->route('bulk-users.upload')->with('success', 'Users imported successfully.');
    }
}
