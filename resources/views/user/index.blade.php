@extends('layouts.admin-lte')

@section('css')
    <link rel="stylesheet" href="{{asset("AdminLTE/bower_components/select2/dist/css/select2.min.css")}}">
    <style>
        .pagination{margin:0}
        .overlay{padding-top:80px}
        #activity{min-height:300px;}
    </style>
@endsection

@section('content')
    <div class="user-index">
        <div class="box search">
            <div class="box-header with-border">
                <h3 class="box-title">{{$page['subTitle'] or ''}}</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    @if(!empty($organizations))
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>组织架构</label>
                                <select class="form-control select2" name="organization_id" style="width: 100%;">
                                    <option value="">请选择</option>
                                    @foreach($organizations as $key=>$item)
                                        <option value="{{$key}}">{{$item}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>中文姓名</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>邮箱</label>
                            <input type="text" class="form-control" name="email">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>电话号码</label>
                            <input type="text" class="form-control" name="mobile">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label><br/>
                            <a class="btn btn-primary btn-search" href="javascript:void(0)"><i class="fa fa-search"></i> 搜索用户</a>
                            <a class="btn btn-success" href="{{route("user.create")}}"><i class="fa fa-plus-circle"></i> 创建用户</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#activity" data-toggle="tab" data-status_inorganization="">全部用户</a></li>
                <li class=""><a href="#inorganization" data-toggle="tab" data-status_inorganization="{{\App\Models\User::INORGANIZATION}}">未分配到组织架构</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="activity">

                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script src="{{asset("AdminLTE/bower_components/select2/dist/js/select2.full.min.js")}}"></script>
    <script>
        $(function () {
            $(".select2").select2();

            //搜索
            $(".btn-search").on('click',function(){
                $(".user-index .nav.nav-tabs li.active a").attr('data-page',1).trigger('click');
            });

            //分页
            $(".user-index").on('click',".pagination li:not('.disabled') a",function(e){
                var page = $(this).attr('href').split('page=')[1];
                $(".user-index .nav.nav-tabs li.active a").attr('data-page',page).trigger('click');
                e.preventDefault();
            });

            //TAB 切换
            $(".user-index").on('click','.nav.nav-tabs a',function(e){
                var href = $(this).attr('href');

                var url = '{{route('user.listing')}}';
                if(e.originalEvent){
                    //重置select2
                    $(".search .select2").each(function(i,item){
                        $(item).find('option').eq(0).prop("selected", 'selected');
                        $(item).trigger('change.select2');
                    });
                    $(this).attr('data-page',1);
                }
                var page = $(this).attr('data-page');
                url += '?organization_id='+$("select[name=organization_id]").val()+'&name='+$("input[name=name]").val()+'&mobile='+$("input[name=mobile]").val()+'&email='+$("input[name=email]").val()+'&status_inorganization='+$(this).attr('data-status_inorganization')+'&page='+page;
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {},
                    dataType: "json",
                    beforeSend: function(){
                        $("#activity").html('<div class="overlay text-center"><img src="{{asset('images/loading.gif')}}" width="80"></div>');
                    },
                    success: function(data){
                        $("#activity").html(data.html);
                    }
                });
            });
            $(".user-index .nav.nav-tabs a:eq(0)").attr('data-page',1).trigger('click');
        })
    </script>
@endsection
