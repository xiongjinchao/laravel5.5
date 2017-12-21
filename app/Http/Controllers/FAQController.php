<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use App\Models\Country;
use App\Models\Knowledge;
use App\Models\KnowledgeCategory;
use App\Models\ModelUpload;
use App\Models\ModelLog;
use App\Models\Organization;
use Illuminate\Http\Request;
use View,Route,Redirect;

class FAQController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:'.Route::current()->getActionName());

        View::share('page',[
            'title' => 'FAQ管理',
            'subTitle' => 'FAQ管理',
            'breadcrumb' => [
                ['url' => '#','label' => 'FAQ管理' ],
                ['url' => route('faq.index'),'label' => 'FAQ管理' ]
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
            'faqs' => FAQ::getList(request()->all()),
        ];
        return view('faq.index', $data);
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
        return view('faq.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\FAQ  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\FAQ $request)
    {
        $faq = new FAQ();
        $faq->fill($request->all());
        $faq->ask_user_id = $request->user()->id;
        $faq->operator = $request->user()->id;
        $faq->status = FAQ::STATUS_NOT_REPLY;
        $faq->asked_at = time();
        if($faq->save()){

            ModelLog::log([
                'model' => 'FAQ',
                'model_id' => $faq->id,
                'content' => '创建FAQ',
            ]);

            $request->session()->flash('success','FAQ创建成功');
        }else{
            $request->session()->flash('error','FAQ创建失败');
        }
        return Redirect::route('faq.index');
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
        $data = [
            'breadcrumb' => [['url' => '#','label' => '更新' ]],
            'faq' => FAQ::find($id),
            'organizations' => Organization::orderBy('lft', 'ASC')->get(),
        ];
        return view('faq.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\FAQ  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\FAQ $request, $id)
    {
        $faq = FAQ::find($id);
        $faq->fill($request->all());
        $faq->operator = $request->user()->id;
        if($faq->content == '' && request('content')!=''){
            $faq->status = FAQ::STATUS_HAS_REPLY;
            $faq->answered_at = time();
        }else if($faq->content != '' && request('content')==''){
            $faq->answer_user_id = 0;
            $faq->status = FAQ::STATUS_NOT_REPLY;
            $faq->answered_at = 0;
        }
        if($faq->save()){

            ModelLog::log([
                'model' => 'FAQ',
                'model_id' => $faq->id,
                'content' => '更新FAQ',
            ]);

            $request->session()->flash('success','FAQ编辑成功');
        }else{
            $request->session()->flash('error','FAQ编辑失败');
        }
        return Redirect::route('faq.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $faq = FAQ::find($id);
        $faq->operator = request()->user()->id;
        $faq->status = FAQ::STATUS_DELETE;
        if($faq->save()){

            ModelLog::log([
                'model' => 'FAQ',
                'model_id' => $id,
                'content' => '删除FAQ',
            ]);
            request()->session()->flash('success','FAQ已成功设为删除');
        }else{
            request()->session()->flash('success','FAQ设为删除失败');
        }
        return Redirect::route('faq.index');
    }

    //转换为知识
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
                    'model' => 'FAQ',
                    'model_id' => $id,
                    'content' => '转换知识',
                ]);
                ModelLog::log([
                    'model' => 'Knowledge',
                    'model_id' => $knowledge->id,
                    'content' => 'FAQ转换',
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

                request()->session()->flash('success','FAQ已成功转换为知识');
            }else{
                request()->session()->flash('error','FAQ转换为知识失败');
            }
            return Redirect::route('faq.index');

        }else {
            //AJAX获取HTML
            $data = [
                'faq' => FAQ::find($id),
                'countries' => Country::all()->toArray(),
                'knowledgeCategories' => KnowledgeCategory::getKnowledgeCategoryOptions(),
                'organizations' => Organization::getOrganizationOptions(),
            ];
            $view = view('faq.change', $data)->render();
            return ['status' => 'success', 'html' => $view];
        }
    }

    //操作日志
    public function log($id)
    {
        $data = [
            'breadcrumb' => [['url' => '#','label' => '查看日志' ]],
            'logs' => ModelLog::where('model','=','FAQ')->where('model_id','=',$id)->orderBy('id','ASC')->get()
        ];
        return view('faq.log',$data);
    }
}
