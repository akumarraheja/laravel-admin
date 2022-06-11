<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Admin;
use Illuminate\Http\Request;

class AppController{

    public function routesHandler(Request $request, $routename=''){
        $cruds = admin_cruds();
        $routekey = preg_replace('/\//', '.', $routename);
        if(!isset($cruds[$routekey])) return abort(404);

        $allpermissions = Admin::getAllPermissions();

        if(!empty($routename) && $request->has('__view')){
            return view('custom::'.str_replace('/', '.', strtolower($routename)).'.'.$request->get('__view'));
        }
    }
}