<?php

namespace Encore\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Collapse;
use Encore\Admin\Widgets\Form;
use Encore\Admin\Widgets\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use function PHPUnit\Framework\directoryExists;

class CrudController extends Controller{
    public function index(Content $content){
        return $content
            ->title('Manage CRUD')
            ->description('Simple Routing Solution')
            ->body((new Collapse())->addMultiple([
                '1'=>[
                    'title'=>'<i class="mdi mdi-puzzle-plus mr-1"></i>New CRUD',
                    'content'=>$this->getForm()->render()
                ],'0'=>[
                    'title'=>'<i class="mdi mdi-puzzle mr-1"></i>Available CRUD',
                    'content'=>$this->getCrudTable()->render()
                ],
            ]));
    }

    public function show(){
        return redirect(admin_url('crud'));
    }

    protected function getForm($data=[]){
        $form = new Form($data);
        $form->text('slug', 'Slug')->rules('required|regex:/(?<!admin)/|min:3', ['regex'=>'Crud slug cannot be "admin"'])->icon('mdi mdi-puzzle');
        $form->text('name', 'Display Name')->rules('required|min:3')->icon('mdi mdi-puzzle');
        $form->select('authtype', 'Authentication')->options(['Guest'=>'Guest', 'Auth'=>'Auth'])->rules('required');
        $form->select('permission', 'Permission')->options(Admin::getAllPermissions()->pluck('name', 'id'));
        $form->action('crud');
        return $form;
    }

    public function store(Request $request){
        $res = $this->getForm()->validate($request);
        if(empty($res)) {
            $create_res = $this->createCrud($request->get('slug'), $request->get('name'), $request->get('authtype'), $request->get('permission'));
            if(!$create_res) {admin_error('Slug already in use'); return;}
            admin_toastr("CRUD has been created");

            $request->session()->regenerate();

            return back();
        } else {
            return back()->withInput()->withErrors($res);
        }
    }

    protected function createCrud($slug, $name, $auth='Guest', $permission='', $override=false){
        $crud_file_path  = base_path('custom').'/cruds/data.json';
        if(!file_exists($crud_file_path)) {$file = fopen($crud_file_path, 'w'); fclose($file);}

        $cruds = json_decode(file_get_contents($crud_file_path), true)??[];

        if(!$override && isset($cruds[$slug])) return false;

        $cruds[$slug]=[
            "name"=>$name,
            "authtype"=>$auth,
            "permission"=>$permission,
        ];
        file_put_contents($crud_file_path, json_encode($cruds));

        // create crud permission

        $this->createSlugController(ucfirst($slug));
        return true;
    }

    protected function getCrudTable(){
        $cruds = admin_cruds();
        $permissions = Admin::getAllPermissions()->keyBy('id');
        
        $headers = ['Slug', 'Name', 'Authentication', 'Permission', 'Actions'];
        $rows = [];
        foreach($cruds as $slug => $crud){
            $rows[]=[
                $slug, $crud['name'], $crud['authtype'], $permissions[$crud['permission']]->name??'', view('admin::crud.crudaction', ['slug'=> $slug])->render()
            ];
        }

        $table = new Table($headers, $rows);
        return $table;
    }

    public function edit(Content $content, $slug){
        $crud = (admin_cruds())[$slug]??'';
        if(empty($crud)) {
            admin_error("CRUD $slug does not exist");

            Session::regenerate();
            return redirect(admin_url('crud'));
        }
        $form = $this->getForm([
            'slug'=>$slug, 'name'=>$crud['name'], 'authtype'=>$crud['authtype'], 'permission'=>$crud['permission']
        ]);
        $form->method('PUT');
        $form->action(admin_url("crud/$slug"));
        return $content->title("Edit CRUD")->breadcrumb(
            [
                'text'=>'Crud', 'icon'=>'puzzle-piece', 'url'=>admin_url('crud')
            ], [
                'text'=>$slug, 'icon'=>'puzzle-piece'
            ])->body($form);
    }

    public function update(Request $request, $slug){
        $res = $this->getForm()->validate($request);
        if(empty($res)) {
            $this->createCrud($request->get('slug'), $request->get('name'), $request->get('authtype'), $request->get('permission'), true);
            if($slug != $request->get('slug')){
                $cruds = admin_cruds(); unset($cruds[$slug]); admin_cruds($cruds);
            }
            admin_toastr("CRUD has been updated");

            $request->session()->regenerate();

            return redirect(admin_url('/crud'));
        } else {
            return redirect(admin_url('/crud'))->withInput()->withErrors($res);
        }
    }

    public function destroy(Request $request, $slug){
        $cruds = admin_cruds();
        if(isset($cruds[$slug])) {

            unset($cruds[$slug]);

            $crud_file_path  = base_path('custom').'/cruds/data.json';
            file_put_contents($crud_file_path, json_encode($cruds));

            admin_toastr("CRUD has been deleted");

            $request->session()->regenerate();

            return redirect(admin_url('crud'));
        }

        admin_error("CRUD $slug does not exist");

        $request->session()->regenerate();

        return redirect(admin_url('crud'));
    }

    protected function createSlugController($slug){
        $namespace = $slug; $classname = $slug;
        if(str_contains($slug, '.')){
            $slug = preg_replace_callback('/(?<=\.)\w{1}/', function($matches){ return ucfirst($matches[0]); }, $slug);
            $pos = strrpos($slug, '.');
            $namespace = str_replace('.', '/', $slug);
            $classname = ucfirst(substr($slug, $pos+1));
        }
        $controllerDir = base_path('custom/packages/'.$namespace.'/Controller');
        if(!file_exists($controllerDir)) mkdir($controllerDir, 0777, true);
        if(!file_exists($controllerDir."/$classname.php")) {
            $file = fopen($controllerDir."/$classname.php", 'w'); fclose($file);
            $stubcontroller = file_get_contents(__DIR__.'/../Console/stubs/AkrController.stub');
            $stubcontroller = str_replace('{{namespace}}', str_replace('/', '\\', $namespace), $stubcontroller);
            $stubcontroller = str_replace('{{classname}}', $classname, $stubcontroller);
            file_put_contents($controllerDir."/$classname.php", $stubcontroller);
        }
        
    }
}