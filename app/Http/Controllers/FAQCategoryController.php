<?php

namespace App\Http\Controllers;

use App\Models\FAQCategory;
use Illuminate\Http\Request;
use View,DB,Route,Redirect;

class FAQCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:'.Route::current()->getActionName());

        View::share('page',[
            'title' => 'FAQ管理',
            'subTitle' => 'FAQ分类',
            'breadcrumb' => [
                ['url' => '#','label' => 'FAQ管理' ],
                ['url' => route('faq-category.index'),'label' => 'FAQ分类' ]
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
            'faqCategories' => FAQCategory::orderBy('lft','ASC')->get(),
        ];
        return view('faq-category.index',$data);
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
            'faqCategories' => FAQCategory::getFAQCategoryOptions(),
        ];
        return view('faq-category.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\FAQCategory  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\FAQCategory $request)
    {
        DB::transaction(function () use($request) {
            $category = new FAQCategory();
            $category->fill($request->all());
            $category->operator = request()->user()->id;
            if ($request->parent > 0) {
                $parent = FAQCategory::find($request->parent);
                FAQCategory::where('lft', '>=', $parent->rgt)->increment('lft', 2);
                FAQCategory::where('rgt', '>=', $parent->rgt)->increment('rgt', 2);
                $category->lft = $parent->rgt;
                $category->rgt = $parent->rgt + 1;
                $category->depth = $parent->depth + 1;
            } else {
                $max = FAQCategory::orderBy('rgt', 'DESC')->first();
                $category->parent = 0;
                $category->lft = $max != '' ? $max->rgt + 1 : 1;
                $category->rgt = $max != '' ? $max->rgt + 2 : 2;
                $category->depth = 1;
            }
            if($category->save()){
                $request->session()->flash('success','FAQ分类创建成功');
            }
        });
        return Redirect::route('faq-category.index');
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
            'faqCategories' => FAQCategory::getFAQCategoryOptions(),
            'faqCategory' => FAQCategory::find($id),
        ];
        return view('faq-category.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\FAQCategory  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\FAQCategory $request, $id)
    {
        DB::transaction(function () use($request,$id) {
            $category = FAQCategory::find($id);
            $category->operator = request()->user()->id;
            $category->save();

            $old = FAQCategory::find($id)->toArray();
            if($old['parent'] == $request->parent){
                $category->fill($request->all());
                $category->save();
            } else if ($request->parent == 0) {
                $tree = FAQCategory::where('lft', '>=', $category->lft)->where('rgt', '<=', $category->rgt)->pluck('id')->toArray();
                FAQCategory::where('lft','>',$category->rgt)->update([
                    'lft'=>DB::raw('lft - '.($category->rgt-$category->lft+1)),
                    'rgt'=>DB::raw('rgt - '.($category->rgt-$category->lft+1))
                ]);
                $max = FAQCategory::whereNotIn('id',$tree)->orderBy('rgt', 'DESC')->first();
                FAQCategory::whereIn('id',$tree)->update([
                    'lft'=>DB::raw('lft + '.($max->rgt-$category->lft+1)),
                    'rgt'=>DB::raw('rgt + '.($max->rgt-$category->lft+1)),
                    'depth'=>DB::raw('depth - '.($category->depth-1))
                ]);
                $category->fill($request->all());
                $category->save();
            } else {
                $parent = FAQCategory::find($request->parent);
                $exist = FAQCategory::where('lft','<',$category->lft)->where('rgt','>',$category->rgt)->where('id','=',$parent->id)->first();
                $tree = FAQCategory::where('lft', '>=', $category->lft)->where('rgt', '<=', $category->rgt)->pluck('id')->toArray();
                if (empty($tree) || in_array($parent->id, $tree)) {
                    $request->session()->flash('error','操作非法，不可以将父类目录放到子目录下面');
                    return Redirect::route('faq-category.index');
                }
                if ($exist == null) {
                    if($parent->lft > $category->lft){
                        $between = $parent->rgt-$category->rgt-1;
                        FAQCategory::where('lft','>',$category->rgt)->where('lft','<',$parent->rgt)->update([
                            'lft'=>DB::raw('lft - '.($category->rgt-$category->lft+1))
                        ]);
                        FAQCategory::where('rgt','>',$category->rgt)->where('rgt','<',$parent->rgt)->update([
                            'rgt'=>DB::raw('rgt - '.($category->rgt-$category->lft+1))
                        ]);
                    }else{
                        $between = $parent->rgt-$category->lft;
                        FAQCategory::where('lft','>',$parent->rgt)->where('lft','<',$category->lft)->update([
                            'lft'=>DB::raw('lft + '.($category->rgt-$category->lft+1))
                        ]);
                        FAQCategory::where('rgt','>=',$parent->rgt)->where('rgt','<',$category->lft)->update([
                            'rgt'=>DB::raw('rgt + '.($category->rgt-$category->lft+1))
                        ]);

                    }
                    FAQCategory::whereIn('id',$tree)->update([
                        'lft'=>DB::raw('lft '.($between>0?'+'.$between:$between)),
                        'rgt'=>DB::raw('rgt '.($between>0?'+'.$between:$between)),
                        'depth'=>DB::raw('depth + '.($parent->depth-$category->depth+1))
                    ]);
                    $category->fill($request->all());
                    $category->save();
                }else{
                    $oldParent = FAQCategory::find($old['parent']);
                    $between = $category->rgt - $category->lft + 1;
                    FAQCategory::where('lft','>',$category->lft)->where('rgt','<',$oldParent->rgt)->update([
                        'lft'=>DB::raw('lft - '.$between),
                        'rgt'=>DB::raw('rgt - '.$between),
                    ]);
                    FAQCategory::where('lft','<',$oldParent->lft)->where('rgt','<',$parent->rgt)->where('rgt','>',$oldParent->rgt)->update([
                        'rgt'=>DB::raw('rgt - '.$between),
                    ]);
                    FAQCategory::where('lft','>',$oldParent->lft)->where('rgt','<',$parent->rgt)->where('rgt','>',$oldParent->rgt)->update([
                        'lft'=>DB::raw('lft - '.$between),
                        'rgt'=>DB::raw('rgt - '.$between),
                    ]);
                    FAQCategory::where('id','=',$oldParent->id)->update([
                        'rgt'=>DB::raw('rgt - '.$between),
                    ]);
                    FAQCategory::whereIn('id',$tree)->update([
                        'lft'=>DB::raw('lft + '.($parent->rgt - $category->rgt-1)),
                        'rgt'=>DB::raw('rgt + '.($parent->rgt - $category->rgt-1)),
                        'depth'=>DB::raw('depth + '.($parent->depth-$category->depth+1))
                    ]);

                    $category->fill($request->all());
                    $category->save();
                }
            }
            $request->session()->flash('success','FAQ分类更新成功');
        });
        return Redirect::route('faq-category.index');
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
            $category = FAQCategory::find($id);

            FAQCategory::where('lft','>=',$category->lft)->where('rgt','<=',$category->rgt)->delete();
            FAQCategory::where('lft','>',$category->lft)->decrement('lft',($category->rgt - $category->lft + 1));
            FAQCategory::where('rgt','>',$category->rgt)->decrement('rgt',($category->rgt - $category->lft + 1));
        });
        request()->session()->flash('success','FAQ分类删除成功');
        return Redirect::route('faq-category.index');
    }

    public function moveUp($id)
    {
        DB::transaction(function () use($id) {
            $category = FAQCategory::find($id);
            $category->operator = request()->user()->id;
            $category->save();

            $previousCategory = FAQCategory::where('rgt', '=', $category->lft - 1)->first();
            $treeCategory = FAQCategory::where('lft', '>=', $category->lft)->where('rgt', '<=', $category->rgt)->pluck('id')->toArray();

            FAQCategory::where('lft', '>=', $previousCategory->lft)->where('rgt', '<=', $previousCategory->rgt)->update([
                'lft'=>DB::raw('lft + '.($category->rgt - $category->lft + 1)),
                'rgt'=>DB::raw('rgt + '.($category->rgt - $category->lft + 1)),
            ]);
            FAQCategory::whereIn('id', $treeCategory)->update([
                'lft'=>DB::raw('lft - '.($previousCategory->rgt - $previousCategory->lft + 1)),
                'rgt'=>DB::raw('rgt - '.($previousCategory->rgt - $previousCategory->lft + 1)),
            ]);
            request()->session()->flash('success','FAQ分类排序成功');
        });
        return Redirect::route('faq-category.index');
    }

    public function moveDown($id)
    {
        DB::transaction(function () use($id) {
            $category = FAQCategory::find($id);
            $category->operator = request()->user()->id;
            $category->save();

            $nextCategory = FAQCategory::where('lft', '=', $category->rgt + 1)->first();
            $treeCategory = FAQCategory::where('lft', '>=', $category->lft)->where('rgt', '<=', $category->rgt)->pluck('id')->toArray();

            FAQCategory::where('lft', '>=', $nextCategory->lft)->where('rgt', '<=', $nextCategory->rgt)->update([
                'lft'=>DB::raw('lft - '.($category->rgt - $category->lft + 1)),
                'rgt'=>DB::raw('rgt - '.($category->rgt - $category->lft + 1)),
            ]);
            FAQCategory::whereIn('id', $treeCategory)->update([
                'lft'=>DB::raw('lft + '.($nextCategory->rgt - $nextCategory->lft + 1)),
                'rgt'=>DB::raw('rgt + '.($nextCategory->rgt - $nextCategory->lft + 1)),
            ]);
            request()->session()->flash('success','FAQ分类排序成功');
        });
        return Redirect::route('faq-category.index');
    }
}
