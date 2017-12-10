@extends('layouts.admin-lte')
@section('css')
    <style>
        .popover{width:350px;max-width:350px;}
        .popover-title{padding:14px;front-size:16px;font-weight:bold;}
        .popover-footer{padding:8px 14px; background:#f7f7f7;border-top:1px solid #ebebeb}
        .operation a{margin-right: 5px;}
    </style>
@endsection
@section('content')
    <div class="role-index">
        <p><a class="btn btn-success" data-toggle="modal" data-target="#modal-create-role"><i class="fa fa-plus-circle"></i> 创建角色</a></p>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{$page['subTitle'] or ''}}</h3>
            </div>
            <div class="box-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>展示名称</th>
                        <th>英文名称</th>
                        <th>描述</th>
                        <th>创建时间</th>
                        <th>更新时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($roles->count() > 0)
                        @foreach($roles as $key=>$item)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td><a href="javascript:void(0)" class="popover-edit-role" data-toggle="popover" data-display_name="{{$item->display_name}}" data-name="{{$item->name}}" data-description="{{$item->description}}" data-update_url="{{ route('role.update',['id'=>$item->id]) }}">{{$item->display_name}}</a></td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->description}}</td>
                                <td>{{$item->created_at}}</td>
                                <td>{{$item->updated_at}}</td>
                                <td class="operation">
                                    <a class="btn btn-sm btn-primary" href="{{ route('role.permission',[$item->id]) }}" title="配置权限"><i class="fa fa-gear"></i> 配置权限</a>
                                    <form action="" method="POST" style="display: inline">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <a class="btn btn-sm btn-danger btn-delete" href="{{ route('role.destroy',[$item->id]) }}" title="删除"><i class="fa fa-trash"></i> 删除</a>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-create-role">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('role.store') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="modal-header bg-blue">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">创建角色</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>展示名称</label>
                                    <input class="form-control" name="display_name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>英文名称</label>
                                    <input class="form-control" name="name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>描述</label>
                                    <textarea class="form-control" rows="2" name="description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> 放弃</button>
                        <button type="submit" class="btn btn-primary pull-right create-role"><i class="fa fa-check"></i> 确认</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function () {
            $("#modal-create-role").on('show.bs.modal',function(){
                $(".popover-edit-role").popover('hide');
            });

            $(".popover-edit-role").popover({
				title:'编辑角色',
				content:'<form action="" method="POST">{{ csrf_field() }}{{ method_field('PUT') }}'+$("#modal-create-role .modal-body").html()+'</form>',
				placement:'right',
                template:'<div class="popover" role="tooltip"><div class="arrow"></div><h2 class="popover-title"></h2><div class="popover-content"></div><div class="popover-footer text-right"><button type="button" class="btn btn-sm btn-default"><i class="fa fa-ban"></i> 放弃</button> <button type="button" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> 确认</button></div></div>',
                html: true,
                trigger:'click',
            });

            $(".popover-edit-role").on('shown.bs.popover',function(){
            	$(this).closest('td').find('form').attr('action',$(this).attr('data-update_url'))
                $(this).closest('td').find('input[name=display_name]').val($(this).attr('data-display_name'));
                $(this).closest('td').find('input[name=name]').val($(this).attr('data-name'));
                $(this).closest('td').find('textarea[name=description]').val($(this).attr('data-description'));
            });

            $(".content").on('click','.popover .btn-default',function(){
                $(this).closest('td').find('.popover-edit-role').trigger('click');
            });
            
            $(".content").on('click','.popover .btn-primary',function(){
                $(this).closest('.popover').find('form').submit();
            });
        })
    </script>
@endsection
