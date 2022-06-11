<?php

namespace Encore\Admin\Controllers;

use Illuminate\Http\Request;

class AppController{

    public function routesHandler(Request $request, $routename=''){
        $cruds = admin_cruds();
        if(!empty($routename) && !in_array(preg_replace('/\//', '.', $routename), array_keys($cruds))) return abort(404);
    }
}