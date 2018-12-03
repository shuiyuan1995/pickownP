<?php

namespace App\Http\Controllers\Admin;

use App\Models\Reward;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RewardsController extends Controller
{
    public function index(Request $request){
        $query = Reward::query()->with('user');
        if ($request->filled('key')){
            $name = $request->input('key');
            $query->where('userid','like', '%'.$name.'%');
        }
        $list = $query->paginate();
        return view('admin.reward.index',compact('list'));
    }
}
