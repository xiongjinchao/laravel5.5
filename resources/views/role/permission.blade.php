@extends('layouts.admin-lte')

@section('css')
    <link rel="stylesheet" href="{{asset("AdminLTE/plugins/iCheck/all.css")}}">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="role-permission">
                <p><a class="btn btn-primary" href="{{route('role.retrieve',['id'=>$role->id])}}"><i class="fa fa-search"></i> 检索权限</a></p>

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">分配权限</h3>
                    </div>
                    <div class="box-body">
                        <div class="content">
                            <form action="{{ route('role.permission',['id'=>$role->id]) }}" method="POST">
                                {{ csrf_field() }}
                                <div class="row">
                                    @foreach($controllerRoles as $key=>$item)
                                        <div class="col-md-3 permission-group">
                                            <h5>
                                                <div class="form-group">
                                                    <label>
                                                        <input type="checkbox" class="check-all"><b>{{$item->display_name}}</b>
                                                    </label>
                                                </div>
                                            </h5>
                                            @if($item->hasManyPermissions()!=null)
                                                @foreach($item->hasManyPermissions() as $permission)
                                                    <?php $action = explode('@',$permission->display_name)[1];?>
                                                    <div class="form-group">
                                                        <label>
                                                            <input type="checkbox" name="permissions[]" class="check-single" {{$role->hasPermission($permission->name)?'checked':''}} value="{{$permission->id}}">
                                                            {{\App\Models\Permission::getPermission($action)}}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        @if(($key+1)%4 == 0)
                                            <div class="clearfix"></div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> 保存</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset("AdminLTE/plugins/iCheck/icheck.min.js")}}"></script>
    <script>
        $(function () {
            $('.role-permission input[type=checkbox]').iCheck({
                checkboxClass: 'icheckbox_square-blue'
            });

            $(".check-all").on('ifChecked',function(){
                $(this).closest('.permission-group').find('.check-single').iCheck('check');
            });
            $(".check-all").on('ifUnchecked',function(){
                $(this).closest('.permission-group').find('.check-single').iCheck('uncheck');
            });

            $(".check-single").on('ifChecked',function(){
                if($(this).closest('.permission-group').find('.check-single:checked').length == $(this).closest('.permission-group').find('.check-single').length){
                    $(this).closest('.permission-group').find('.check-all').iCheck('check');
                }
            });

            $(".check-single").on('ifUnchecked',function(){
                if($(this).closest('.permission-group').find('.check-single:checked').length == 0) {
                    $(this).closest('.permission-group').find('.check-all').iCheck('uncheck');
                }
            });
        })
    </script>
@endsection
