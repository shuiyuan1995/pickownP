<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\TransactionInfoResource;
use App\Models\TransactionInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionInfosController extends Controller
{
    public function index(Request $request)
    {
        $query = TransactionInfo::query()->with('issus_user')->with('income_user');
        if ($request->filled('key')) {
            $name = $request->input('key');
            $query->where(function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
                // $query->orWhere();
            });
        }
        $list = $query->paginate();
        return view('admin.transaction_info.index', compact('list'));
    }

    public function edit($id)
    {
        $entity = TransactionInfo::findOrFail($id);
        return view('admin.transaction_info.edit', compact('entity'));
    }

    public function update(Request $request, $id)
    {
        $data = TransactionInfo::findOrFail($id);
        $data->update($request->all());
        return redirect(route('admin.transaction_info.index'))->with('flash_message', '添加成功');
    }
    public function show($id){
        $data = TransactionInfo::findOrFail($id);
        return new TransactionInfoResource($data);
    }
}
