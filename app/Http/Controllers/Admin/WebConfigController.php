<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WebConfig;

class WebConfigController extends Controller
{
    public function index(){
        $web_config = WebConfig::get();
        
        return view('admin.web_config.index', compact('web_config'));
    }

    public function create(){
        return view('admin.web_config.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'content' => 'required',
        ]);
        $web_config = new WebConfig();
        $web_config->name = $request->input('name');
        $web_config->content = $request->input('content');
        $web_config->save();

        return redirect(route('admin.web_config.index'))->with('flash_message', '添加成功');
    }

    public function edit($id)
    {
        //
        $web_config = WebConfig::findOrFail($id);

        return view('admin.web_config.edit', compact('web_config'));
    }

    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'name' => 'required',
            'content' => 'required',
        ]);
        $web_config = WebConfig::findOrFail($id);
        $web_config->name = $request->input('name');
        $web_config->content = $request->input('content');

        $web_config->save();

        return redirect(route('admin.web_config.index'))->with('flash_message', '修改成功');
    }

    public function destroy($id)
    {
        //
        $web_config = WebConfig::findOrFail($id);

        $web_config->delete();

        return redirect(route('admin.web_config.index'))->with('flash_message', '删除成功');
    }
}
