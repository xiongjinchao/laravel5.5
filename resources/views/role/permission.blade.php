@extends('layouts.admin-lte')

@section('css')
    <link rel="stylesheet" href="{{asset("AdminLTE/plugins/iCheck/all.css")}}">
@endsection

@section('content')
    <div class="role-permission">
    	<p><a class="btn btn-primary" href="{{route('role.retrieve-permission',['id'=>$role->id])}}"><i class="fa fa-plus-circle"></i> 检索权限</a></p>	
		
		<div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">分配权限</h3>
            </div>
            <div class="box-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>选择</th>
                        <th>展示名称</th>
                        <th>英文名称</th>
                        <th>描述</th>
                        <th>更新时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($permissions->count() > 0)
                        @foreach($permissions as $key=>$item)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td><input type="checkbox"></td>
                                <td><a href="javascript:void(0)" class="popover-edit-role" data-toggle="popover" data-display_name="{{$item->display_name}}" data-name="{{$item->name}}" data-description="{{$item->description}}" data-update_url="{{ route('role.update',['id'=>$item->id]) }}">{{$item->display_name}}</a></td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->description}}</td>
                                <td>{{$item->updated_at}}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>    
    </div>

@endsection

@section('js')
    <script src="{{asset("AdminLTE/plugins/iCheck/icheck.min.js")}}"></script>
    <script>
        $(function () {
            
        })
    </script>
@endsection
