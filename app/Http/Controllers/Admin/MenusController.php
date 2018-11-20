<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenusController extends Controller
{
    public function index()
    {
        $list = Menu::orderBy('sort')->orderBy('id')->get();

        return view('admin.menu.index', compact('list'));
    }

    public function create()
    {
        return view('admin.menu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Menu::create($request->all());

        return redirect(route('admin.menu.index'));
    }

    public function edit($id)
    {
        $permission = Menu::findOrFail($id);

        return view('admin.menu.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $permission = Menu::findOrFail($id);

        $permission->update($request->all());

        return redirect(route('admin.menu.index'))->with('flash_message', '修改成功');
    }

    public function destroy($id)
    {
        $menu = Menu::with('children')->findOrFail($id);

        // 移除用户的菜单
        $menu->users()->detach($menu->children->push($menu));

        // 删除子菜单
        $menu->children()->delete();

        // 删除菜单
        $menu->delete();

        return redirect(route('admin.menu.index'))->with('flash_message', '删除成功');
    }
}
