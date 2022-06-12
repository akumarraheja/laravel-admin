<?php

namespace Encore\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Collapse;
use Encore\Admin\Widgets\Form;
use Encore\Admin\Widgets\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class TinkerController extends Controller{
    public function index(Content $content){
        return $content
            ->title('Tinker')
            ->body(view('admin::tinker.index'));
    }

    public function store(Request $request){
        $tempfilename = 'temp_'.Str::random(10);
        $temppath = __DIR__.'/../../resources/views/tinker/'.$tempfilename.'.blade.php';
        file_put_contents($temppath, $request->get('tinkercode'));
        return view('admin::tinker.'.$tempfilename);
        @unlink($temppath);
    }
}