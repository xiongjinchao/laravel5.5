@extends('layouts.admin-lte')

@section('css')
    <link rel="stylesheet" href="{{asset("AdminLTE/bower_components/select2/dist/css/select2.min.css")}}">
    <link rel="stylesheet" href="{{asset("AdminLTE/plugins/iCheck/all.css")}}">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="user-edit">
                <div class="box">
                    <form action="{{ route('user.update',['id'=>$user->id]) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="box-header with-border">
                            <h3 class="box-title">{{$page['subTitle'] or ''}}</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>中文姓名</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <input class="form-control" name="name" placeholder="请输入用户姓名" value="{{ old('name',$user->name) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>所属组织架构</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-sitemap"></i>
                                            </div>
                                            <select class="form-control select2" name="organization_id" style="width: 100%;">
                                                <option value="0">请选择</option>
                                                @foreach($organizations as $key=>$item)
                                                    <option {{old('organization_id',$user->organization_id) == $key?'selected':''}} value="{{$key}}">{{$item}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="help-block">{{\App\Models\Organization::getOrganizationPath($user->organization_id)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>邮箱</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-envelope"></i>
                                            </div>
                                            <input class="form-control" name="email" placeholder="请输入邮箱" value="{{old('email',$user->email)}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>手机号码</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-phone"></i>
                                            </div>
                                            <input class="form-control" name="mobile" placeholder="请输入手机号码" value="{{old('mobile',$user->mobile)}}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>管理员状态</label>
                                        <p class="user-status">
                                            @foreach($user->getStatusOptions() as $key => $item)
                                                <input type="radio" name="status" value="{{$key}}" {{$user->status == $key?'checked':''}}>&nbsp;&nbsp;{{$item}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            @endforeach
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>操作人</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <input class="form-control" value="{{$user->hasOneUser!=null?$user->hasOneUser->name:''}}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>创建时间</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input class="form-control" value="{{$user->created_at}}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($user->updated_at != '')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>更新时间</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input class="form-control" value="{{$user->updated_at}}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> 保存用户</button>
                            <a href="javascript:void(0)" class="btn btn-default" data-toggle="modal" data-target="#modal-reset-password"><i class="fa fa-refresh"></i> 修改密码</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="modal-reset-password">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('user.password',['id'=>$user->id]) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="modal-header bg-blue">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">修改密码</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>设置新密码</label>
                                    <input type="password" class="form-control" name="password" placeholder="请输新密码">
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

@endsection

@section('js')
    <script src="{{asset("AdminLTE/bower_components/select2/dist/js/select2.full.min.js")}}"></script>
    <script src="{{asset("AdminLTE/plugins/iCheck/icheck.min.js")}}"></script>
    <script>
        $(function () {
            $('.select2').select2();

            $('.user-status input[type="radio"]').iCheck({
                radioClass:'iradio_minimal-blue'
            });

            //默认密码
            $("#modal-reset-password").on('show.bs.modal',function(){
                $("input[name=password]").val('123456');
            });

            $("#modal-reset-password .confirm-reset-password").on('click',function(){
                $(this).closest('form').submit();
            });
        })
    </script>
@endsection
