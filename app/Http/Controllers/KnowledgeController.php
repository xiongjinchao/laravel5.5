<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Knowledge;
use App\Models\KnowledgeCategory;
use App\Models\ModelLog;
use Illuminate\Http\Request;
use View,Route,Redirect;

class KnowledgeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:'.Route::current()->getActionName());

        View::share('page',[
            'title' => '知识管理',
            'subTitle' => '知识管理',
            'breadcrumb' => [
                ['url' => '#','label' => '知识管理' ],
                ['url' => route('knowledge.index'),'label' => '知识管理' ]
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
            'countries' => Country::all()->toArray(),
            'knowledgeCategories' => KnowledgeCategory::getKnowledgeCategoryOptions(),
            'hotCountries' => Knowledge::getHotCountries(),
        ];
        return view('knowledge.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'breadcrumb' => [['url' => '#','label' => '更新' ]],
            'countries' => Country::all()->toArray(),
            'knowledgeCategories' => KnowledgeCategory::getKnowledgeCategoryOptions(),
        ];
        return view('knowledge.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $knowledge = new Knowledge();
        $knowledge->fill($request->all());
        $knowledge->operator = $request->user()->id;
        $knowledge->author = $request->user()->id;
        $knowledge->status = Knowledge::STATUS_NEW;
        if($knowledge->save()){

            ModelLog::log([
                'model' => 'Knowledge',
                'model_id' => $knowledge->id,
                'content' => '创建知识',
            ]);

            $request->session()->flash('success','知识创建成功');
        }else{
            $request->session()->flash('error','知识创建失败');
        }
        return Redirect::route('knowledge.index');
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
            'countries' => Country::all()->toArray(),
            'knowledgeCategories' => KnowledgeCategory::getKnowledgeCategoryOptions(),
            'knowledge' => Knowledge::find($id),
        ];
        return view('knowledge.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $knowledge = Knowledge::find($id);
        $knowledge->fill($request->all());
        $knowledge->operator = $request->user()->id;
        if($knowledge->save()){

            ModelLog::log([
                'model' => 'Knowledge',
                'model_id' => $knowledge->id,
                'content' => '更新知识',
            ]);

            $request->session()->flash('success','知识编辑成功');
        }else{
            $request->session()->flash('error','知识编辑失败');
        }
        return Redirect::route('knowledge.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Knowledge::find($id)->delete()){

            ModelLog::log([
                'model' => 'Knowledge',
                'model_id' => $id,
                'content' => '删除知识',
            ]);

            request()->session()->flash('success','知识已成功删除');
        }else{
            request()->session()->flash('error','知识删除失败');
        }
        return Redirect::route('knowledge.index');
    }

    public function tab()
    {
        $data = [
            'knowledge' => Knowledge::getlist(request()->all())
        ];
        $view = view('knowledge.tab', $data)->render();
        return ['status' => 'success', 'html' => $view];
    }

    public function copy($id)
    {
        $knowledge = Knowledge::copyKnowledge($id);
        if($knowledge != null){
            ModelLog::log([
                'model' => 'Knowledge',
                'model_id' => $knowledge->id,
                'content' => '复制知识',
            ]);
            request()->session()->flash('success','知识已成功复制');
        }else{
            request()->session()->flash('error','知识复制失败');
        }
        return Redirect::route('knowledge.index');
    }

    //提交到待审核
    public function submit($id)
    {
        $knowledge = Knowledge::find($id);
        $knowledge->status = Knowledge::STATUS_WAIT_AUDIT;
        $knowledge->operator = request()->user()->id;
        if($knowledge->save()){

            ModelLog::log([
                'model' => 'Knowledge',
                'model_id' => $knowledge->id,
                'content' => '提交审核',
            ]);

            request()->session()->flash('success','知识已成功提交');
        }else{
            request()->session()->flash('error','知识提交失败');
        }
        return Redirect::route('knowledge.index');
    }

    //审核成功或失败
    public function audit($id, $status)
    {
        $knowledge = Knowledge::find($id);
        $knowledge->status = $status;
        $knowledge->operator = request()->user()->id;
        if($knowledge->save()){

            ModelLog::log([
                'model' => 'Knowledge',
                'model_id' => $knowledge->id,
                'content' => $status == Knowledge::STATUS_FAIL_AUDIT?'审核失败':'审核成功',
            ]);

            request()->session()->flash('success','操作成功');
        }else{
            request()->session()->flash('error','操作失败');
        }
        return Redirect::route('knowledge.index');
    }

    //上线或下线
    public function publish($id, $status)
    {
        $knowledge = Knowledge::find($id);
        $knowledge->status = $status;
        $knowledge->operator = request()->user()->id;
        if($knowledge->save()){

            ModelLog::log([
                'model' => 'Knowledge',
                'model_id' => $knowledge->id,
                'content' => $status == Knowledge::STATUS_ONLINE?'执行上线':'执行下线',
            ]);

            request()->session()->flash('success','操作成功');
        }else{
            request()->session()->flash('error','操作失败');
        }
        return Redirect::route('knowledge.index');
    }

    //操作日志
    public function log($id)
    {
        $data = [
            'breadcrumb' => [['url' => '#','label' => '查看日志' ]],
            'logs' => ModelLog::where('model','=','Knowledge')->where('model_id','=',$id)->orderBy('id','ASC')->get()
        ];
        return view('knowledge.log',$data);
    }
}
