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
        $tinkercode = "<?php\n\n\n\n\n\n\n\n";
        try{$tinkercode = file_get_contents($this->getViewPath($user));} catch (Throwable $e){}
        return $content
            ->title('Tinker')
            ->body(view('admin::tinker.index', ['tinkercode'=>str_replace("\r", '', str_replace("\n", '\n', addslashes($tinkercode)))]));
    }

    public function store(Request $request){
        try{
            $user = Admin::user();
            file_put_contents($this->getViewPath($user), $request->get('tinkercode'));
            $view = view('admin::tinker.user_'.$user->id)->render();
            return $view;
        } catch (Throwable $e){
            dump($e);
        }
    }
    
    protected function getViewPath($user){
        $viewpath = __DIR__.'/../../resources/views/tinker/user_'.$user->id.'.blade.php';
        return $viewpath;
    }

}