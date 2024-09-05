<?php
namespace App\Http\Controllers;

use App\Models\Fakechats;
use App\Models\Users;
use Illuminate\Http\Request;

class FakechatsController extends Controller
{

    public function verify(Request $request)
    {
        $fakechatIds = $request->input('fakechat_ids', []);
        
        foreach ($fakechatIds as $fakechatId) {
            $fakechat = Fakechats::find($fakechatId);
            if ($fakechat) {
                $fakechat->status = 1; // Set status to Fake
                $fakechat->save();
            }
        }

        return response()->json(['success' => true]);
    }

    public function notFake(Request $request)
    {
        $fakechatIds = $request->input('fakechat_ids', []);
        
        foreach ($fakechatIds as $fakechatId) {
            $fakechat = Fakechats::find($fakechatId);
            if ($fakechat) {
                $fakechat->status = 0; // Set status to Not-Fake
                $fakechat->save();
            }
        }

        return response()->json(['success' => true]);
    }
    public function index(Request $request)
    {
        $query = Fakechats::query()->with('user'); // Ensure eager loading if needed
    
        if ($request->has('status')) {
            $status = $request->input('status');
            $query->where('status', $status);
        } else {
            // By default, fetch pending trips
            $query->where('status', 0);
        }

    
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('user_id', 'like', "%{$search}%"); // Adjust based on your search criteria
        }
    
        // Order by ID in descending order
        $fakechats = $query->orderBy('id', 'desc')->paginate(10);
        
        return view('fakechats.index', compact('fakechats'));
    }
    
    public function destroy(Fakechats $fakechat)
    {
        $fakechat->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}

