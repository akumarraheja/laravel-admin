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


class TinkerController extends Controller{
    public function index(Content $content){
        return $content
            ->title('Tinker')
            ->body(view('admin::tinker.index'));
    }

    public function store(Request $request){
        
    }
}