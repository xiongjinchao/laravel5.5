<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th style="width: 10px">#</th>
            <th>中文姓名</th>
            <th>所属组织架构</th>
            <th>邮箱</th>
            <th>手机号码</th>
            <th>管理员状态</th>
            <th>操作人</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @if($users->count() > 0)
            @foreach($users as $key=>$item)
                <tr>
                    <td><b>{{$key+1}}</b></td>
                    <td>{{$item->name}}</td>
                    <td>{{\App\Models\Organization::getOrganizationPath($item->organization_id)}}</td>
                    <td>{{$item->email}}</td>
                    <td>{{$item->mobile}}</td>
                    <td>{{\App\Models\User::getStatusOptions($item->status)}}</td>
                    <td>
                        @if($item instanceof \App\Models\User)
                            {{$item->hasOneUser != null ? $item->hasOneUser->name : ''}}
                        @else
                            {{\App\Models\User::getUser($item->id)->name}}
                        @endif
                    </td>
                    <td class="operation">
                        <a class="btn btn-sm btn-primary" href="{{ route('user.edit',[$item->id]) }}" title="更新"><i class="fa fa-edit"></i> 更新</a>
                        <a class="btn btn-sm btn-success btn-assignment-role" href="{{ route('user.assignment',[$item->id]) }}" title="分配角色"><i class="fa fa-edit"></i> 分配角色</a>
                        <a class="btn btn-sm btn-warning btn-reset-password" href="{{ route('user.password',[$item->id]) }}" title="重置密码"><i class="fa fa-refresh"></i> 重置密码</a>
                        <form action="" method="POST" style="display: inline">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <a class="btn btn-sm btn-danger btn-delete" href="{{ route('user.destroy',[$item->id]) }}" title="删除"><i class="fa fa-trash"></i> 删除</a>
                        </form>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<div class="clearfix">
    {{$users->links()}}
</div>

<div class="modal fade" id="modal-assignment-role">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                {{ csrf_field() }}
                <div class="modal-header bg-blue">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">分配角色</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label>系统角色</label>
                        </div>
                        @foreach($roles as $key=>$item)
                            @if($item->description == '系统')
                                <div class="col-md-3">
                                    <label><input type="checkbox" value="{{$item->id}}}"></label>
                                    {{$item->display_name}}
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>自定义角色</label>
                        </div>
                        @foreach($roles as $key=>$item)
                            @if($item->description != '系统')
                                <div class="col-md-3">
                                    <label><input type="checkbox" value="{{$item->id}}}"></label>
                                    {{$item->display_name}}
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> 放弃</button>
                    <button type="button" class="btn btn-primary pull-right confirm-reset-password"><i class="fa fa-check"></i> 确认</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-reset-password">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                {{ csrf_field() }}
                <div class="modal-header bg-blue">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">修改密码</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>设置新密码</label>
                                <input type="password" class="form-control" name="password" placeholder="请输新密码" value="123456">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> 放弃</button>
                    <button type="button" class="btn btn-primary pull-right confirm-reset-password"><i class="fa fa-check"></i> 确认</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#modal-assignment-role input[type=checkbox]').iCheck({
        checkboxClass: 'icheckbox_minimal-blue'
    });

    $(".btn-assignment-role").on('click',function(e){
        e.preventDefault();
        var action = $(this).attr('href');
        $("#modal-assignment-role form").attr('action',action);
        $("#modal-assignment-role").modal();
    });

    $(".btn-reset-password").on('click',function(e){
        e.preventDefault();
        var action = $(this).attr('href');
        $("#modal-reset-password form").attr('action',action);
        $("#modal-reset-password").modal();
    });

    $("#modal-reset-password .confirm-reset-password").on('click',function(){
        $(this).closest('form').submit();
    });
</script>