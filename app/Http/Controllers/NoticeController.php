<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Notice;
use App\Models\Organization;
use App\Models\Knowledge;
use App\Models\KnowledgeCategory;
use App\Models\ModelLog;
use App\Models\ModelUpload;
use Illuminate\Http\Request;
use View,Route,Redirect;

class NoticeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:'.Route::current()->getActionName());

        View::share('page',[
            'title' => '公告管理',
            'subTitle' => '',
            'breadcrumb' => [
                ['url' => '#','label' => '公告管理' ]
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'organizations' => Organization::orderBy('lft', 'ASC')->get(),
            'notices' => Notice::getList(request()->all()),
        ];
        return view('notice.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'breadcrumb' => [['url' => '#','label' => '创建' ]],
            'organizations' => Organization::orderBy('lft', 'ASC')->get(),
        ];
        return view('notice.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Notice  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\Notice $request)
    {
        $notice = new Notice();
        $notice->fill($request->all());
        $notice->operator = $request->user()->id;
        $notice->author = $request->user()->id;
        $notice->status = Notice::STATUS_WAIT_PUBLISH;
        if($notice->save()){

            ModelLog::log([
                'model' => 'Notice',
                'model_id' => $notice->id,
                'content' => '创建公告',
            ]);

            if(!empty($request->upload_id)){
                $ids = explode(',',$request->upload_id);
                foreach($ids as $item){
                    $modelUpload = new ModelUpload();
                    $modelUpload->model = 'Notice';
                    $modelUpload->model_id = $notice->id;
                    $modelUpload->upload_id = $item;
                    $modelUpload->save();
                }
            }

            $request->session()->flash('success','公告创建成功');
        }else{
            $request->session()->flash('error','公告创建失败');
        }
        return Redirect::route('notice.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $uploads = ModelUpload::getUploads([
            'model' => 'Notice',
            'model_id' => $id,
        ]);
        $data = [
            'breadcrumb' => [['url' => '#','label' => '更新' ]],
            'notice' => Notice::find($id),
            'organizations' => Organization::orderBy('lft', 'ASC')->get(),
            'upload_id' => implode(',',array_column($uploads, 'key')),
            'preview' => json_encode(array_column($uploads, 'downloadUrl'),JSON_UNESCAPED_SLASHES),
            'config' => json_encode($uploads,JSON_UNESCAPED_SLASHES)
        ];
        return view('notice.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Notice  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\Notice $request, $id)
    {
        $notice = Notice::find($id);
        $notice->fill($request->all());
        $notice->operator = $request->user()->id;
        if($notice->save()){

            ModelLog::log([
                'model' => 'Notice',
                'model_id' => $notice->id,
                'content' => '更新公告',
            ]);

            ModelUpload::where('model', '=', 'Notice')->where('model_id', '=', $notice->id)->delete();
            if(!empty($request->upload_id)){
                $ids = explode(',',$request->upload_id);
                foreach($ids as $item){
                    $modelUpload = new ModelUpload();
                    $modelUpload->model = 'Notice';
                    $modelUpload->model_id = $notice->id;
                    $modelUpload->upload_id = $item;
                    $modelUpload->save();
                }
            }

            $request->session()->flash('success','公告编辑成功');
        }else{
            $request->session()->flash('error','公告编辑失败');
        }
        return Redirect::route('notice.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Notice::find($id)->delete()){

            ModelLog::log([
                'model' => 'Notice',
                'model_id' => $id,
                'content' => '删除公告',
            ]);

            request()->session()->flash('success','公告已成功删除');
        }else{
            request()->session()->flash('error','公告删除失败');
        }
        return Redirect::route('notice.index');
    }

    public function change($id)
    {
        if(request()->isMethod('post')){
            //保存
            $knowledge = new Knowledge();
            $knowledge->fill(request()->all());
            $knowledge->tags = str_replace('，',',',request('tags',''));
            $knowledge->operator = request()->user()->id;
            $knowledge->author = request()->user()->id;
            $knowledge->status = Knowledge::STATUS_NEW;
            if($knowledge->save()){

                ModelLog::log([
                    'model' => 'Notice',
                    'model_id' => $id,
                    'content' => '转换知识',
                ]);
                ModelLog::log([
                    'model' => 'Notice',
                    'model_id' => $knowledge->id,
                    'content' => '公告转换',
                ]);

                if(request('upload_id')){
                    $ids = explode(',',request('upload_id'));
                    foreach($ids as $item){
                        $modelUpload = new ModelUpload();
                        $modelUpload->model = 'Knowledge';
                        $modelUpload->model_id = $knowledge->id;
                        $modelUpload->upload_id = $item;
                        $modelUpload->save();
                    }
                }

                request()->session()->flash('success','公告已成功转换为知识');
            }else{
                request()->session()->flash('error','公告转换为知识失败');
            }
            return Redirect::route('faq.index');

        }else {
            //AJAX获取HTML
            $uploads = ModelUpload::getUploads([
                'model' => 'Notice',
                'model_id' => $id,
            ]);
            $data = [
                'notice' => Notice::find($id),
                'countries' => Country::all()->toArray(),
                'knowledgeCategories' => KnowledgeCategory::getKnowledgeCategoryOptions(),
                'organizations' => Organization::getOrganizationOptions(),
                'upload_id' => implode(',',array_column($uploads, 'key')),
                'preview' => json_encode(array_column($uploads, 'downloadUrl'),JSON_UNESCAPED_SLASHES),
                'config' => json_encode($uploads,JSON_UNESCAPED_SLASHES)
            ];
            $view = view('notice.change', $data)->render();
            return ['status' => 'success', 'html' => $view];
        }
    }

    //操作日志
    public function log($id)
    {
        $data = [
            'breadcrumb' => [['url' => '#','label' => '查看日志' ]],
            'logs' => ModelLog::where('model','=','Notice')->where('model_id','=',$id)->orderBy('id','ASC')->get()
        ];
        return view('notice.log',$data);
    }
}
