<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Knowledge;
use App\Models\KnowledgeCategory;
use Illuminate\Http\Request;
use View,Redirect;

class KnowledgeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('auth');

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
        if($this->checkEntrustAuth() !== true){
            return $this->checkEntrustAuth();
        }
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
        if($this->checkEntrustAuth() !== true){
            return $this->checkEntrustAuth();
        }
        return view('knowledge.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($this->checkEntrustAuth() !== true){
            return $this->checkEntrustAuth();
        }
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($this->checkEntrustAuth() !== true){
            return $this->checkEntrustAuth();
        }
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
        if($this->checkEntrustAuth() !== true){
            return $this->checkEntrustAuth();
        }
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
        if($this->checkEntrustAuth() !== true){
            return $this->checkEntrustAuth();
        }

        $knowledge = Knowledge::find($id);
        $knowledge->fill($request->all());
        $knowledge->operator = $request->user()->id;
        if($knowledge->save()){
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
        if($this->checkEntrustAuth() !== true){
            return $this->checkEntrustAuth();
        }

        if(Knowledge::find($id)->delete()){
            request()->session()->flash('success','知识已成功删除');
        }else{
            request()->session()->flash('error','知识删除失败');
        }
        return Redirect::route('knowledge.index');
    }

    public function listing()
    {
        if($this->checkEntrustAuth() !== true){
            return $this->checkEntrustAuth();
        }

        $data = [
            'knowledge' => Knowledge::getlist(request()->all())
        ];
        $view = view('knowledge.listing', $data)->render();
        return ['status' => 'success', 'html' => $view];
    }

    public function copy($id)
    {
        if($this->checkEntrustAuth() !== true){
            return $this->checkEntrustAuth();
        }

        if(Knowledge::copyKnowledge($id)){
            request()->session()->flash('success','知识已成功复制');
        }else{
            request()->session()->flash('error','知识复制失败');
        }
        return Redirect::route('knowledge.index');
    }
}
