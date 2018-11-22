<?php

namespace App\Http\Controllers\Admin;

use App\Models\GamePartition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class GamePartitionsController extends Controller
{
    public function index(Request $request)
    {
        $query = GamePartition::query();

        if ($request->filled('key')) {
            $name = $request->input('key');
            $query->where(function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
                // $query->orWhere('key', 'like', '%'.$name.'%');
            });
        }

        $list = $query->paginate();
        return view('admin.game_partition.index', compact('list'));
    }

    public function create()
    {
        return view('admin.game_partition.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:game_partitions,name',
        ]);
        $data = new GamePartition();
        $data->name = $request->input('name');
        $data->sum = $request->input('sum') * 10000;
        $data->up = $request->input('up') * 100;
        $data->down = $request->input('down') * 100;
        $data->number = $request->input('number');
        $data->status = $request->input('status');
        $data->save();
        return redirect(route('admin.game_partition.index'))->with('flash_message', '添加成功');
    }

    public function edit($id)
    {
        $entity = GamePartition::findOrFail($id);
        return view('admin.game_partition.edit', compact('entity'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', Rule::unique('game_partitions', 'name')->ignore($id, 'id')],

        ]);
        $sum = 'sum';
        $data = GamePartition::findOrFail($id);
        $data->name = $request->input('name');
        $data->$sum = $request->input('sum') * 10000;
        $data->up = $request->input('up') * 100;
        $data->down = $request->input('down') * 100;
        $data->number = $request->input('number');
        $data->status = $request->input('status');
        $data->save();
        return redirect(route('admin.game_partition.index'))->with('flash_message', '添加成功');

    }

    public function destroy($id)
    {
        $type = KeywordsType::findOrFail($id);

        $type->delete();

        return redirect(route('admin.game_partition.index'))->with('flash_message', '删除成功');

    }
}
