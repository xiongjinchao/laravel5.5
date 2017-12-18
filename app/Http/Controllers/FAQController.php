<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
            'faq' => FAQ::find($id),
            'organizations' => Organization::orderBy('lft', 'ASC')->get(),
        ];
        return view('faq.edit', $data);
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
        $faq = FAQ::find($id);
        $faq->fill($request->all());
        $faq->operator = $request->user()->id;
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
        //
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
