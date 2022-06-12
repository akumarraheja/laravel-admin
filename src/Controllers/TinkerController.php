<?php

namespace Encore\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Throwable;

class TinkerController extends Controller{
    public function index(Content $content){
        $user = Admin::user();
        $tinkercode = $user->getData('tinkercode')??"<?php\n\n\n\n\n\n\n\n";
        return $content
            ->title('Tinker')
            ->body(view('admin::tinker.index', ['tinkercode'=>str_replace("\r", '', str_replace("\n", '\n', addslashes($tinkercode)))]));
    }

    public function store(Request $request){
        $user = Admin::user();
        $tinkercode = $request->get('tinkercode');
        try{
            file_put_contents($this->getViewPath($user), $tinkercode);
            $view = view('admin::tinker.user_'.$user->id)->render();
            if(empty($view)) return 'Nothing to display';
            return $view;
        } catch (Throwable $e){
            dump($e);
        } finally {
            @unlink($this->getViewPath($user));
            $user->setData('tinkercode', $tinkercode);
        }
    }
    
    protected function getViewPath($user){
        $viewpath = __DIR__.'/../../resources/views/tinker/user_'.$user->id.'.blade.php';
        return $viewpath;
    }

}