<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use DB,View,Route,Redirect;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:'.Route::current()->getActionName());

        View::share('page',[
            'title' => '系统设置',
            'subTitle' => '组织架构',
            'breadcrumb' => [
                ['url' => '#','label' => '系统设置' ],
                ['url' => route('organization.index'),'label' => '组织架构' ]
            ]
        ]);
    }

    public function index()
    {
        $data = [
            'organizations' => Organization::orderBy('lft','ASC')->get(),
        ];
        return view('organization.index',$data);
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
            'organizations' => Organization::getOrganizationOptions(),
        ];
        return view('organization.create',$data);
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
            $category = new Organization();
            $category->fill($request->all());
            $category->operator = request()->user()->id;
            if ($request->parent > 0) {
                $parent = Organization::find($request->parent);
                Organization::where('lft', '>=', $parent->rgt)->increment('lft', 2);
                Organization::where('rgt', '>=', $parent->rgt)->increment('rgt', 2);
                $category->lft = $parent->rgt;
                $category->rgt = $parent->rgt + 1;
                $category->depth = $parent->depth + 1;
            } else {
                $max = Organization::orderBy('rgt', 'DESC')->first();
                $category->parent = 0;
                $category->lft = $max != '' ? $max->rgt + 1 : 1;
                $category->rgt = $max != '' ? $max->rgt + 2 : 2;
                $category->depth = 1;
            }
            if($category->save()){
                $request->session()->flash('success','组织架构创建成功');
            }
        });
        return Redirect::route('organization.index');
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
            'organizations' => Organization::getOrganizationOptions(),
            'organization' => Organization::find($id),
        ];
        return view('organization.edit',$data);
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
            $category = Organization::find($id);
            $category->operator = request()->user()->id;
            $category->save();

            $old = Organization::find($id)->toArray();
            if($old['parent'] == $request->parent){
                $category->fill($request->all());
                $category->save();
            } else if ($request->parent == 0) {
                $tree = Organization::where('lft', '>=', $category->lft)->where('rgt', '<=', $category->rgt)->pluck('id')->toArray();
                Organization::where('lft','>',$category->rgt)->update([
                    'lft'=>DB::raw('lft - '.($category->rgt-$category->lft+1)),
                    'rgt'=>DB::raw('rgt - '.($category->rgt-$category->lft+1))
                ]);
                $max = Organization::whereNotIn('id',$tree)->orderBy('rgt', 'DESC')->first();
                Organization::whereIn('id',$tree)->update([
                    'lft'=>DB::raw('lft + '.($max->rgt-$category->lft+1)),
                    'rgt'=>DB::raw('rgt + '.($max->rgt-$category->lft+1)),
                    'depth'=>DB::raw('depth - '.($category->depth-1))
                ]);
                $category->fill($request->all());
                $category->save();
            } else {
                $parent = Organization::find($request->parent);
                $exist = Organization::where('lft','<',$category->lft)->where('rgt','>',$category->rgt)->where('id','=',$parent->id)->first();
                $tree = Organization::where('lft', '>=', $category->lft)->where('rgt', '<=', $category->rgt)->pluck('id')->toArray();
                if (empty($tree) || in_array($parent->id, $tree)) {
                    $request->session()->flash('error','操作非法，不可以将父类目录放到子目录下面');
                    return Redirect::route('organization.index');
                }
                if ($exist == null) {
                    if($parent->lft > $category->lft){
                        $between = $parent->rgt-$category->rgt-1;
                        Organization::where('lft','>',$category->rgt)->where('lft','<',$parent->rgt)->update([
                            'lft'=>DB::raw('lft - '.($category->rgt-$category->lft+1))
                        ]);
                        Organization::where('rgt','>',$category->rgt)->where('rgt','<',$parent->rgt)->update([
                            'rgt'=>DB::raw('rgt - '.($category->rgt-$category->lft+1))
                        ]);
                    }else{
                        $between = $parent->rgt-$category->lft;
                        Organization::where('lft','>',$parent->rgt)->where('lft','<',$category->lft)->update([
                            'lft'=>DB::raw('lft + '.($category->rgt-$category->lft+1))
                        ]);
                        Organization::where('rgt','>=',$parent->rgt)->where('rgt','<',$category->lft)->update([
                            'rgt'=>DB::raw('rgt + '.($category->rgt-$category->lft+1))
                        ]);

                    }
                    Organization::whereIn('id',$tree)->update([
                        'lft'=>DB::raw('lft '.($between>0?'+'.$between:$between)),
                        'rgt'=>DB::raw('rgt '.($between>0?'+'.$between:$between)),
                        'depth'=>DB::raw('depth + '.($parent->depth-$category->depth+1))
                    ]);
                    $category->fill($request->all());
                    $category->save();
                }else{
                    $oldParent = Organization::find($old['parent']);
                    $between = $category->rgt - $category->lft + 1;
                    Organization::where('lft','>',$category->lft)->where('rgt','<',$oldParent->rgt)->update([
                        'lft'=>DB::raw('lft - '.$between),
                        'rgt'=>DB::raw('rgt - '.$between),
                    ]);
                    Organization::where('lft','<',$oldParent->lft)->where('rgt','<',$parent->rgt)->where('rgt','>',$oldParent->rgt)->update([
                        'rgt'=>DB::raw('rgt - '.$between),
                    ]);
                    Organization::where('lft','>',$oldParent->lft)->where('rgt','<',$parent->rgt)->where('rgt','>',$oldParent->rgt)->update([
                        'lft'=>DB::raw('lft - '.$between),
                        'rgt'=>DB::raw('rgt - '.$between),
                    ]);
                    Organization::where('id','=',$oldParent->id)->update([
                        'rgt'=>DB::raw('rgt - '.$between),
                    ]);
                    Organization::whereIn('id',$tree)->update([
                        'lft'=>DB::raw('lft + '.($parent->rgt - $category->rgt-1)),
                        'rgt'=>DB::raw('rgt + '.($parent->rgt - $category->rgt-1)),
                        'depth'=>DB::raw('depth + '.($parent->depth-$category->depth+1))
                    ]);

                    $category->fill($request->all());
                    $category->save();
                }
            }
            $request->session()->flash('success','组织架构更新成功');
        });
        return Redirect::route('organization.index');
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
            $category = Organization::find($id);

            Organization::where('lft','>=',$category->lft)->where('rgt','<=',$category->rgt)->delete();
            Organization::where('lft','>',$category->lft)->decrement('lft',($category->rgt - $category->lft + 1));
            Organization::where('rgt','>',$category->rgt)->decrement('rgt',($category->rgt - $category->lft + 1));
        });
        request()->session()->flash('success','组织架构删除成功');
        return Redirect::route('organization.index');
    }

    public function moveUp($id)
    {
        DB::transaction(function () use($id) {
            $category = Organization::find($id);
            $category->operator = request()->user()->id;
            $category->save();

            $previousCategory = Organization::where('rgt', '=', $category->lft - 1)->first();
            $treeCategory = Organization::where('lft', '>=', $category->lft)->where('rgt', '<=', $category->rgt)->pluck('id')->toArray();

            Organization::where('lft', '>=', $previousCategory->lft)->where('rgt', '<=', $previousCategory->rgt)->update([
                'lft'=>DB::raw('lft + '.($category->rgt - $category->lft + 1)),
                'rgt'=>DB::raw('rgt + '.($category->rgt - $category->lft + 1)),
            ]);
            Organization::whereIn('id', $treeCategory)->update([
                'lft'=>DB::raw('lft - '.($previousCategory->rgt - $previousCategory->lft + 1)),
                'rgt'=>DB::raw('rgt - '.($previousCategory->rgt - $previousCategory->lft + 1)),
            ]);
            request()->session()->flash('success','组织架构排序成功');
        });
        return Redirect::route('organization.index');
    }

    public function moveDown($id)
    {
        DB::transaction(function () use($id) {
            $category = Organization::find($id);
            $category->operator = request()->user()->id;
            $category->save();

            $nextCategory = Organization::where('lft', '=', $category->rgt + 1)->first();
            $treeCategory = Organization::where('lft', '>=', $category->lft)->where('rgt', '<=', $category->rgt)->pluck('id')->toArray();

            Organization::where('lft', '>=', $nextCategory->lft)->where('rgt', '<=', $nextCategory->rgt)->update([
                'lft'=>DB::raw('lft - '.($category->rgt - $category->lft + 1)),
                'rgt'=>DB::raw('rgt - '.($category->rgt - $category->lft + 1)),
            ]);
            Organization::whereIn('id', $treeCategory)->update([
                'lft'=>DB::raw('lft + '.($nextCategory->rgt - $nextCategory->lft + 1)),
                'rgt'=>DB::raw('rgt + '.($nextCategory->rgt - $nextCategory->lft + 1)),
            ]);
            request()->session()->flash('success','组织架构排序成功');
        });
        return Redirect::route('organization.index');
    }
}
