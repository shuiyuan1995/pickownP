<?php

namespace App\Http\Controllers\Admin;

use http\Client\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MyLogsController extends Controller
{
    /**
     * List all logs.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\View\View
     */
    public function listLogs(Request $request)
    {
        $logs_dir = str_replace('\\', '/', base_path()) . '/storage/logs/';
        $rows = [];
        $arr = scandir($logs_dir);
        if (is_array($arr)) {
            foreach ($arr as $item => $value) {
                if ($value == '.' || $value == '..' || $value == '.gitignore') {

                }else{
                    $rows[] = $value;
                }
            }
        }


        return view('admin.my_logs.logs', compact('rows'));
    }
    /**
     * Download the log
     *
     * @param  Request  $request
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Request $request)
    {
        $filename = $request->input('file');
        $logs_dir = str_replace('\\', '/', base_path()) . '/storage/logs/';
        return response()->download($logs_dir .$filename,config('app.env') . '-'.$filename );
    }
}
