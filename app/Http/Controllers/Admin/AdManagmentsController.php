<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdPositions;
use App\Models\AdManagments;

class AdManagmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $query = AdManagments::query()->orderBy('ad_id', 'asc')->orderBy('sort', 'asc')->with('adposition');

        $list = $query->paginate();

        return view('admin.ad_managments.index', compact('list', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.ad_managments.create');
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
            'ad_id' => 'required',
            'name' => 'required',
        ]);
        $ad_managments = new AdManagments();
        if ($request->hasFile('img_url') && $request->input('type') !=3) {
            $img_url = $request->file('img_url')->store('ad_managment');
        }
        if ($request->input('type') ==3 ) {
            $img_url = $request->input('img_url_text');
        }
        $ad_managments->name = $request->input('name');
        $ad_managments->type = $request->input('type');
        $ad_managments->ad_id = $request->input('ad_id');
        $ad_managments->img_url = $img_url;
        $ad_managments->sort = $request->input('sort');
        $ad_managments->save();

        return redirect(route('admin.ad_managments.index'))->with('flash_message', '添加成功');
    }

    /**
     * 查询广告位.
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
        $ad_managments = AdManagments::findOrFail($id);
        $ad_positions = AdPositions::select(['id','name'])->findOrFail($ad_managments->ad_id);//dd($ad_positions);
        return view('admin.ad_managments.edit',compact('ad_managments','ad_positions'));
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
            'ad_id' => 'required',
            'name' => 'required',
        ]);
        $ad_managments = AdManagments::findOrFail($id);
        $img_url = $ad_managments->img_url;
        $ad_managments->name = $request->input('name');
        $ad_managments->ad_id = $request->input('ad_id');
        if ($request->hasFile('img_url') && $request->input('type') !=3) {
            $img_url = $request->file('img_url')->store('ad_managment');
        }
        if ($request->input('type') ==3 ) {
            $img_url = $request->input('img_url_text');
        }
        $ad_managments->img_url = $img_url;
        $ad_managments->sort = $request->input('sort');
        $ad_managments->save();

        return redirect(route('admin.ad_managments.index'))->with('flash_message', '添加成功');
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
        $ad_managments = AdManagments::findOrFail($id);

        $ad_managments->delete();

        return redirect(route('admin.ad_managments.index'))->with('flash_message', '删除成功');
    }
}
