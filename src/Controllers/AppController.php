<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;

class AppController{

    public function routesHandler(Request $request, $routename=''){
        dump(Admin::guard()->user());
        $cruds = admin_cruds();
        $routekey = preg_replace('/\//', '.', $routename);
        if(!empty($routename) && !isset($cruds[$routekey])) return abort(404);

        if(!empty($routename) && $cruds[$routekey]['authtype']=='Auth'){
            
            $allpermissions = Admin::getAllPermissions();
        }

        if(!empty($routename) && $request->has('__view')){
            return view('custom::'.str_replace('/', '.', strtolower($routename)).'.'.$request->get('__view'));
        }
    }
}