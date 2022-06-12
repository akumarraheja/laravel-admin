<?php

namespace Encore\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;

class AppController{

    public function routesHandler(Request $request, $routename=''){
        /**
         * @var User $user
         */
        $user = Admin::user();
        if(empty($routename)) return view('custom::home', ['user'=>$user]);
        [$routekey, $controller] = $this->getActiveRoute($routename);

        $cruds = admin_cruds();
        if(!isset($cruds[$routekey])) return abort(404);

        if($cruds[$routekey]['authtype']=='Auth'){
            if(empty($user)) return redirect()->to(url('/auth'));
            $permission = $cruds[$routekey]['permission'];
            if(!empty($permission) && !$user->can($permission) && !$user->can('*')){
                return abort(403, 'Sorry, You\'re not authorized to perform this action');
            }
        }
        if($request->has('__view')){
            return view('custom::'.$routekey.'.'.$request->get('__view'));
        }

        $res = $this->startController($request, $routename, $routekey, $controller);
        if($res && $res=='Illuminate\View\View'){
            return view('custom::'.$res->getName(), $res->getData());
        } else return $res;
    }

    protected function getActiveRoute($routename){
        $routesarr = array_map( function($name){ return ucfirst($name); }, explode('/', $routename));

        for($i=(count($routesarr)-1); $i>=0; $i--){
            if($i==0){
                $classname = 'App\\Custom\\'.$routesarr[$i].'\\Controller\\'.$routesarr[$i];
            } else {
                $classname = 'App\\Custom\\'.(implode('/', array_slice($routesarr, 0, $i))).'\\'.$routesarr[$i].'\\Controller\\'.$routesarr[$i];
            }
            if(class_exists($classname)) return [implode('.', array_map(function($name){return lcfirst($name);}, array_slice($routesarr, 0, $i+1))), $classname];
        }
        return ['', ''];
    }

    protected function startController($request, $routename, $routekey, $controller){
        $methodsmap = [
            "GET"=>["index", 'show'],
            "POST"=>["store", ""],
            "UPDATE"=>["", "update"],
            "DELETE"=>["", "destroy"],

        ];
        $initialRoute = str_replace('.', '/', $routekey);
        if($initialRoute != $routename){ $id = explode($initialRoute, $routename, 2)[1]; } else { $id=false; }
        return $this->runController($controller, $methodsmap[$request->method()][0], $id);
    }

    protected function runController($classname, $method, $id=false){
        if(empty($method)) return abort(403);
        $controller = new $classname;
        return $controller->$method();
    }
}