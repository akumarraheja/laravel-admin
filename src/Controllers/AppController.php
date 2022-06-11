<?php

namespace Encore\Admin\Controllers;

use Illuminate\Http\Request;

class AppController{

    public function routesHandler(Request $request, $routename=''){
        $cruds = admin_cruds();
        if(!empty($routename) && !in_array(preg_replace('/\//', '.', $routename), array_keys($cruds))) return abort(404);
        if(!empty($routename) && $request->has('__view')){
            return view('custom::'.str_replace('/', '.', strtolower($routename)).'.'.$request->get('__view'));
        }
    }
}