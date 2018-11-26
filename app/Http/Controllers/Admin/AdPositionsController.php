<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdPositions;

class AdPositionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = AdPositions::query();

        // if ($request->filled('key')) {
        //     $name = $request->input('key');
        //     $query->where(function ($query) use ($name) {
        //         $query->where('name', 'like', '%'.$name.'%');
        //         $query->orWhere('key', 'like', '%'.$name.'%');
        //     });
        // }

        $list = $query->paginate();
        
        return view('admin.ad_positions.index', compact('list'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.ad_positions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'type' => 'required',
            'name' => 'required'
        ]);

        $ad_position = new AdPositions();
        $ad_position->type = $request->input('type');
        $ad_position->name = $request->input('name');
        $ad_position->save();

        return redirect(route('admin.ad_positions.index'))->with('flash_message', '添加成功');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $ad_position = AdPositions::findOrFail($id);

        return view('admin.ad_positions.edit', compact('ad_position'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'type' => 'required',
            'name' => 'required',
        ]);
        $ad_position = AdPositions::findOrFail($id);
        $ad_position->type = $request->input('type');
        $ad_position->name = $request->input('name');

        $ad_position->save();

        return redirect(route('admin.ad_positions.index'))->with('flash_message', '修改成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $ad_position = AdPositions::findOrFail($id);

        $ad_position->admanagment()->delete();

        $ad_position->delete();

        return redirect(route('admin.ad_positions.index'))->with('flash_message', '删除成功');
    }
}
