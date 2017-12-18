@extends('layouts.admin-lte')

@section('content')
    <div class="organization-index">
        <p><a class="btn btn-success" href="{{route("organization.create")}}"><i class="fa fa-plus-circle"></i> 创建组织架构</a></p>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{$page['subTitle'] or ''}}</h3>
            </div>
            <div class="box-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th width="45%">名称</th>
                            <th>操作人</th>
                            <th>操作时间</th>
                            <th>操作</th>
                            <th>排序</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($organizations->count() > 0)
                            @foreach($organizations as $key=>$item)
                                <tr>
                                    <td><b>{{$key+1}}</b></td>
                                    <td>{!!$item->getSpace().($item->rgt-$item->lft >1 ? '<b>'.$item->name.'</b>':$item->name)!!}</td>
                                    <td>{{$item->hasOneUser!=null?$item->hasOneUser->name:''}}</td>
                                    <td>{{$item->updated_at}}</td>
                                    <td class="operation">
                                        <a class="btn btn-sm btn-primary" href="{{ route('organization.edit',[$item->id]) }}" title="更新"><i class="fa fa-edit"></i> 更新</a>
                                        <form action="" method="POST" style="display: inline">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <a class="btn btn-sm btn-danger btn-delete" href="{{ route('organization.destroy',[$item->id]) }}" title="删除"><i class="fa fa-trash"></i> 删除</a>
                                        </form>
                                    </td>
                                    <td class="sort">
                                        @if($item->lft>1&&($item->lft!=$item->getParent()->lft+1))
                                            <a class="btn btn-sm btn-warning" href="{{ route('organization.move-up',[$item->id]) }}" title="上移"><i class="fa fa-arrow-up"></i> 上移</a>
                                        @else
                                            <a class="btn btn-sm btn-default disabled" href="javascript:void(0)" title="上移"><i class="fa fa-arrow-up"></i> 上移</a>
                                        @endif

                                        @if($item->id!=$item->getLastBrother()->id&&($item->rgt!=$item->getParent()->rgt-1))
                                            <a class="btn btn-sm btn-warning" href="{{ route('organization.move-down',[$item->id]) }}" title="下移"><i class="fa fa-arrow-down"></i> 下移</a>
                                        @else
                                            <a class="btn btn-sm btn-default disabled" href="javascript:void(0)" title="下移"><i class="fa fa-arrow-down"></i> 下移</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
