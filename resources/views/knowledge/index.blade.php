@extends('layouts.admin-lte')

@section('css')
    <link rel="stylesheet" href="{{asset("AdminLTE/bower_components/select2/dist/css/select2.min.css")}}">
@endsection

@section('content')
    <div class="knowledge-index">
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
                    @if(!empty($knowledgeCategories))
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>知识目录</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-sitemap"></i>
                                    </div>
                                    <select class="form-control select2" name="category_id" style="width: 100%;">
                                        <option value="">请选择</option>
                                        @foreach($knowledgeCategories as $key=>$item)
                                            <option value="{{$key}}">{{$item}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($countries))
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>选择国家</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-globe"></i>
                                    </div>
                                    <select class="form-control select2" name="country_id" style="width: 100%;">
                                        <option value="">请选择</option>
                                        @foreach($countries as $item)
                                            <option {{$item['audit'] == 1?'disabled="disabled"':''}} value="{{$item['id']}}">{{$item['country'].' '.$item['country_name_en'].''}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>&nbsp;</label><br/>
                            <a class="btn btn-primary btn-search" href="javascript:void(0)"><i class="fa fa-search"></i> 搜索知识</a>
                            <a class="btn btn-success" href="{{route("knowledge.create")}}"><i class="fa fa-plus-circle"></i> 创建知识</a>
                        </div>
                    </div>
                </div>
                @if(!empty($hotCountries))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>热门国家</label>
                                <p class="hot-country">
                                    @foreach($hotCountries as $item)
                                        <a href="javascript:void(0)" data-country_id="{{$item->country_id}}">{{$item->country}}</a>
                                    @endforeach
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#activity" data-toggle="tab" data-status="">全部</a></li>
                <li class=""><a href="#new" data-toggle="tab" data-status="{{\App\Models\Knowledge::STATUS_NEW}}">新建</a></li>
                <li class=""><a href="#wait-audit" data-toggle="tab" data-status="{{\App\Models\Knowledge::STATUS_WAIT_AUDIT}}">待审核</a></li>
                <li class=""><a href="#wait-publish" data-toggle="tab" data-status="{{\App\Models\Knowledge::STATUS_WAIT_PUBLISH}}">待发布</a></li>
                <li class=""><a href="#fail-audit" data-toggle="tab" data-status="{{\App\Models\Knowledge::STATUS_FAIL_AUDIT}}">审核失败</a></li>
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
                $(".knowledge-index .nav.nav-tabs li.active a").attr('data-page',1).trigger('click');
            });

            //热门国家
            $(".hot-country a").on('click',function(){
                var country_id = $(this).attr('data-country_id');
                $("select[name=country_id]").val(country_id);
                $("select[name=country_id]").trigger('change.select2');
                $(".btn-search").trigger('click');
            });

            //分页
            $(".knowledge-index").on('click',".pagination li:not('.disabled') a",function(e){
                var page = $(this).attr('href').split('page=')[1];
                $(".knowledge-index .nav.nav-tabs li.active a").attr('data-page',page).trigger('click');
                e.preventDefault();
            });

            //TAB 切换
            $(".knowledge-index").on('click','.nav.nav-tabs a',function(e){
                var href = $(this).attr('href');

                var url = '{{route('knowledge.tab')}}';
                if(e.originalEvent){
                    //重置select2
                    $(".search .select2").each(function(i,item){
                        $(item).find('option').eq(0).prop("selected", 'selected');
                        $(item).trigger('change.select2');
                    });
                    $(this).attr('data-page',1);
                }
                var page = $(this).attr('data-page');
                url += '?title={{request('title')}}&category_id='+$("select[name=category_id]").val()+'&country_id='+$("select[name=country_id]").val()+'&status='+$(this).attr('data-status')+'&page='+page;
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {},
                    dataType: "json",
                    beforeSend: function(){
                        $("#activity").html('<div class="overlay text-center"><img src="{{asset('images/loading.gif')}}" width="80"></div>');
                    },
                    success: function(data){
                        if(data.status == 'success') {
                            $("#activity").html(data.html);
                        }else{
                            $("#activity").html('<div class="callout callout-danger"><h4>操作提醒</h4>'+data.message+'</div>');
                        }
                    },
                    error: function(data){
                        $("#activity").html('<div class="callout callout-danger"><h4>操作提醒</h4>'+data.responseJSON.message+'</div>');
                    }
                });
            });
            $(".knowledge-index .nav.nav-tabs a:eq(0)").attr('data-page',1).trigger('click');
        })
    </script>
@endsection
