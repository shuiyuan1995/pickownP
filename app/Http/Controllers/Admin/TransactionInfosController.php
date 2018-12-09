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
        if ($request->filled('type')){
            $query->where('type',$request->input('type'));
        }
        if ($request->input('user_type') == 1){
            if ($request->filled('user')) {
                $issus_user = $request->input('user');
                $query->whereHas('issus_user',function ($q) use ($issus_user){
                    $q->where('name','like','%'.$issus_user.'%');
                });
            }
        }else{
            if ($request->filled('user')){
                $income = $request->input('user');
                $query->whereHas('income_user',function ($q) use ($income){
                    $q->where('name','like','%'.$income.'%');
                });
            }
        }
        if ($request->filled('begin_time')) {
            $key = $request->input('begin_time');
            $query->where('created_at','>=',$key);
        }
        if ($request->filled('end_time')) {
            $key = $request->input('end_time');
            $query->where('created_at','<=',$key);
        }
        $data = [1=>'发出者用户名',2=>'获得者用户名'];
        $list = $query->paginate();
        $typeArr = (new TransactionInfo())->typeArr;
        return view('admin.transaction_info.index', compact('list','typeArr','data'));
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
