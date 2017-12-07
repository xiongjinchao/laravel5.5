<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeCategory;
use Illuminate\Http\Request;
use View,DB,Redirect;

class KnowledgeCategoryController extends Controller
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
            'subTitle' => '知识目录',
            'breadcrumb' => [
                ['url' => '#','label' => '知识管理' ],
                ['url' => route('knowledge-category.index'),'label' => '知识目录' ]
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
            'knowledgeCategories' => KnowledgeCategory::orderBy('lft','ASC')->get(),
        ];
        return view('knowledge-category.index',$data);
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
            'knowledgeCategories' => KnowledgeCategory::getKnowledgeCategoryOptions(),
        ];
        return view('knowledge-category.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::transaction(function () use($request) {
            $category = new KnowledgeCategory();
            $category->fill($request->all());
            $category->operator = request()->user()->id;
            if ($request->parent > 0) {
                $parent = KnowledgeCategory::find($request->parent);
                KnowledgeCategory::where('lft', '>=', $parent->rgt)->increment('lft', 2);
                KnowledgeCategory::where('rgt', '>=', $parent->rgt)->increment('rgt', 2);
                $category->lft = $parent->rgt;
                $category->rgt = $parent->rgt + 1;
                $category->depth = $parent->depth + 1;
            } else {
                $max = KnowledgeCategory::orderBy('rgt', 'DESC')->first();
                $category->parent = 0;
                $category->lft = $max != '' ? $max->rgt + 1 : 1;
                $category->rgt = $max != '' ? $max->rgt + 2 : 2;
                $category->depth = 1;
            }
            if($category->save()){
                $request->session()->flash('success','知识目录创建成功');
            }
        });
        return Redirect::route('knowledge-category.index');
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
            'knowledgeCategories' => KnowledgeCategory::getKnowledgeCategoryOptions(),
            'knowledgeCategory' => KnowledgeCategory::find($id),
        ];
        return view('knowledge-category.edit',$data);
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

        DB::transaction(function () use($request,$id) {
            $category = KnowledgeCategory::find($id);
            $category->operator = request()->user()->id;
            $category->save();

            $old = KnowledgeCategory::find($id)->toArray();
            if($old['parent'] == $request->parent){
                $category->fill($request->all());
                $category->save();
            } else if ($request->parent == 0) {
                $tree = KnowledgeCategory::where('lft', '>=', $category->lft)->where('rgt', '<=', $category->rgt)->pluck('id')->toArray();
                KnowledgeCategory::where('lft','>',$category->rgt)->update([
                    'lft'=>DB::raw('lft - '.($category->rgt-$category->lft+1)),
                    'rgt'=>DB::raw('rgt - '.($category->rgt-$category->lft+1))
                ]);
                $max = KnowledgeCategory::whereNotIn('id',$tree)->orderBy('rgt', 'DESC')->first();
                KnowledgeCategory::whereIn('id',$tree)->update([
                    'lft'=>DB::raw('lft + '.($max->rgt-$category->lft+1)),
                    'rgt'=>DB::raw('rgt + '.($max->rgt-$category->lft+1)),
                    'depth'=>DB::raw('depth - '.($category->depth-1))
                ]);
                $category->fill($request->all());
                $category->save();
            } else {
                $parent = KnowledgeCategory::find($request->parent);
                $exist = KnowledgeCategory::where('lft','<',$category->lft)->where('rgt','>',$category->rgt)->where('id','=',$parent->id)->first();
                $tree = KnowledgeCategory::where('lft', '>=', $category->lft)->where('rgt', '<=', $category->rgt)->pluck('id')->toArray();
                if (empty($tree) || in_array($parent->id, $tree)) {
                    $request->session()->flash('error','操作非法，不可以将父类目录放到子目录下面');
                    return Redirect::route('knowledge-category.index');
                }
                if ($exist == null) {
                    if($parent->lft > $category->lft){
                        $between = $parent->rgt-$category->rgt-1;
                        KnowledgeCategory::where('lft','>',$category->rgt)->where('lft','<',$parent->rgt)->update([
                            'lft'=>DB::raw('lft - '.($category->rgt-$category->lft+1))
                        ]);
                        KnowledgeCategory::where('rgt','>',$category->rgt)->where('rgt','<',$parent->rgt)->update([
                            'rgt'=>DB::raw('rgt - '.($category->rgt-$category->lft+1))
                        ]);
                    }else{
                        $between = $parent->rgt-$category->lft;
                        KnowledgeCategory::where('lft','>',$parent->rgt)->where('lft','<',$category->lft)->update([
                            'lft'=>DB::raw('lft + '.($category->rgt-$category->lft+1))
                        ]);
                        KnowledgeCategory::where('rgt','>=',$parent->rgt)->where('rgt','<',$category->lft)->update([
                            'rgt'=>DB::raw('rgt + '.($category->rgt-$category->lft+1))
                        ]);

                    }
                    KnowledgeCategory::whereIn('id',$tree)->update([
                        'lft'=>DB::raw('lft '.($between>0?'+'.$between:$between)),
                        'rgt'=>DB::raw('rgt '.($between>0?'+'.$between:$between)),
                        'depth'=>DB::raw('depth + '.($parent->depth-$category->depth+1))
                    ]);
                    $category->fill($request->all());
                    $category->save();
                }else{
                    $oldParent = KnowledgeCategory::find($old['parent']);
                    $between = $category->rgt - $category->lft + 1;
                    KnowledgeCategory::where('lft','>',$category->lft)->where('rgt','<',$oldParent->rgt)->update([
                        'lft'=>DB::raw('lft - '.$between),
                        'rgt'=>DB::raw('rgt - '.$between),
                    ]);
                    KnowledgeCategory::where('lft','<',$oldParent->lft)->where('rgt','<',$parent->rgt)->where('rgt','>',$oldParent->rgt)->update([
                        'rgt'=>DB::raw('rgt - '.$between),
                    ]);
                    KnowledgeCategory::where('lft','>',$oldParent->lft)->where('rgt','<',$parent->rgt)->where('rgt','>',$oldParent->rgt)->update([
                        'lft'=>DB::raw('lft - '.$between),
                        'rgt'=>DB::raw('rgt - '.$between),
                    ]);
                    KnowledgeCategory::where('id','=',$oldParent->id)->update([
                        'rgt'=>DB::raw('rgt - '.$between),
                    ]);
                    KnowledgeCategory::whereIn('id',$tree)->update([
                        'lft'=>DB::raw('lft + '.($parent->rgt - $category->rgt-1)),
                        'rgt'=>DB::raw('rgt + '.($parent->rgt - $category->rgt-1)),
                        'depth'=>DB::raw('depth + '.($parent->depth-$category->depth+1))
                    ]);

                    $category->fill($request->all());
                    $category->save();
                }
            }
            $request->session()->flash('success','知识目录更新成功');
        });
        return Redirect::route('knowledge-category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::transaction(function () use($id) {
            $category = KnowledgeCategory::find($id);

            KnowledgeCategory::where('lft','>=',$category->lft)->where('rgt','<=',$category->rgt)->delete();
            KnowledgeCategory::where('lft','>',$category->lft)->decrement('lft',($category->rgt - $category->lft + 1));
            KnowledgeCategory::where('rgt','>',$category->rgt)->decrement('rgt',($category->rgt - $category->lft + 1));
        });
        request()->session()->flash('success','知识目录删除成功');
        return Redirect::route('knowledge-category.index');
    }

    public function moveUp($id)
    {
        DB::transaction(function () use($id) {
            $category = KnowledgeCategory::find($id);
            $category->operator = request()->user()->id;
            $category->save();

            $previousCategory = KnowledgeCategory::where('rgt', '=', $category->lft - 1)->first();
            $treeCategory = KnowledgeCategory::where('lft', '>=', $category->lft)->where('rgt', '<=', $category->rgt)->pluck('id')->toArray();

            KnowledgeCategory::where('lft', '>=', $previousCategory->lft)->where('rgt', '<=', $previousCategory->rgt)->update([
                'lft'=>DB::raw('lft + '.($category->rgt - $category->lft + 1)),
                'rgt'=>DB::raw('rgt + '.($category->rgt - $category->lft + 1)),
            ]);
            KnowledgeCategory::whereIn('id', $treeCategory)->update([
                'lft'=>DB::raw('lft - '.($previousCategory->rgt - $previousCategory->lft + 1)),
                'rgt'=>DB::raw('rgt - '.($previousCategory->rgt - $previousCategory->lft + 1)),
            ]);
            request()->session()->flash('success','知识目录排序成功');
        });
        return Redirect::route('knowledge-category.index');
    }

    public function moveDown($id)
    {
        DB::transaction(function () use($id) {
            $category = KnowledgeCategory::find($id);
            $category->operator = request()->user()->id;
            $category->save();

            $nextCategory = KnowledgeCategory::where('lft', '=', $category->rgt + 1)->first();
            $treeCategory = KnowledgeCategory::where('lft', '>=', $category->lft)->where('rgt', '<=', $category->rgt)->pluck('id')->toArray();

            KnowledgeCategory::where('lft', '>=', $nextCategory->lft)->where('rgt', '<=', $nextCategory->rgt)->update([
                'lft'=>DB::raw('lft - '.($category->rgt - $category->lft + 1)),
                'rgt'=>DB::raw('rgt - '.($category->rgt - $category->lft + 1)),
            ]);
            KnowledgeCategory::whereIn('id', $treeCategory)->update([
                'lft'=>DB::raw('lft + '.($nextCategory->rgt - $nextCategory->lft + 1)),
                'rgt'=>DB::raw('rgt + '.($nextCategory->rgt - $nextCategory->lft + 1)),
            ]);
            request()->session()->flash('success','知识目录排序成功');
        });
        return Redirect::route('knowledge-category.index');
    }
}
