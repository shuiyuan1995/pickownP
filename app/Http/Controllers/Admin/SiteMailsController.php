<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SiteMail;

class SiteMailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = SiteMail::query()->with('getUser');

        $list = $query->paginate();
        
        return view('admin.site_mails.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.site_mails.create');
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
            'title' => 'required',
            'content' => 'required',
        ]);

        $site_mail = new SiteMail();
        $site_mail->type = 2;
        $site_mail->userid = 0;
        $site_mail->title = $request->input('title');
        $site_mail->content = $request->input('content');
        $site_mail->status = $request->input('status');
        $site_mail->save();
        $msg= ['1'=>'保存草稿成功','2'=>'发布成功'];

        return redirect(route('admin.site_mails.index'))->with('flash_message', $msg[$site_mail->status]);
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
        $site_mail = SiteMail::findOrFail($id);

        return view('admin.site_mails.show',compact('site_mail'));
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
    }
}
