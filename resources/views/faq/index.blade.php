@extends('layouts.admin-lte')


@section('css')
    <link rel="stylesheet" href="{{asset("AdminLTE/bower_components/select2/dist/css/select2.min.css")}}">
    <link rel="stylesheet" href="{{asset("AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.css")}}">
@endsection

@section('content')
    <div class="faq-index">
        <div class="box search">
            <div class="box-header with-border">
                <h3 class="box-title">{{$page['subTitle'] or ''}}</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <form action="{{ route('faq.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>问题编号</label>
                                <input class="form-control" name="id">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>问题分类</label>
                                <select class="form-control select2" name="category_id" style="width: 100%;">
                                    <option value="">请选择</option>
                                    @foreach(\App\Models\FAQCategory::getFAQCategoryOptions() as $key=>$item)
                                        <option value="{{$key}}" {{request()->category_id == $key?'selected':''}}>{{$item}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>问题状态</label>
                                <select class="form-control select2" name="status" style="width: 100%;">
                                    <option value="">请选择</option>
                                    @foreach(\App\Models\FAQ::getStatusOptions() as $key => $item)
                                        <option value="{{$key}}" {{request()->status == $key?'selected':''}}>{{$item}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if(!empty($organizations))
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>提问人</label>
                                <select class="form-control select2" name="ask_user_id" style="width: 100%;">
                                    <option value="">请选择</option>
                                    @foreach($organizations as $item)
                                        <option disabled="disabled">{{$item->getSpace().$item->name}}</option>
                                        @if($item->hasManyUsers!=null)
                                            @foreach($item->hasManyUsers as $user)
                                                <option value="{{$user->id}}" {{request()->ask_user_id == $user->id?'selected':''}}>{{'┃ '.$item->getSpace().$user->name}}</option>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        @if(!empty($organizations))
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>回答人</label>
                                    <select class="form-control select2" name="answer_user_id" style="width: 100%;">
                                        <option value="">请选择</option>
                                        @foreach($organizations as $item)
                                            <option disabled="disabled">{{$item->getSpace().$item->name}}</option>
                                            @if($item->hasManyUsers!=null)
                                                @foreach($item->hasManyUsers as $user)
                                                    <option value="{{$user->id}}" {{request()->answer_user_id == $user->id?'selected':''}}>{{'┃ '.str_replace('┗ ','┣ ',$item->getSpace()).$user->name}}</option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>提问时间</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" name="ask_time_range" class="form-control pull-right" id="ask-time-range">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>回答时间</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" name="answer_time_range" class="form-control pull-right" id="answer-time-range">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>&nbsp;</label><br/>
                                <button class="btn btn-primary btn-search"><i class="fa fa-search"></i> 搜索FAQ</button>
                                <a class="btn btn-warning btn-reset" href="javascript:void(0)"><i class="fa fa-remove"></i> 清空条件</a>
                                <a class="btn btn-success" href="{{route("faq.create")}}"><i class="fa fa-plus-circle"></i> 创建FAQ</a>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{$page['subTitle'] or ''}}</h3>
            </div>
            <div class="box-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>提问</th>
                        <th>状态</th>
                        <th>分类</th>
                        {{--<th>回答</th>--}}
                        <th>提问人</th>
                        <th>指派人</th>
                        <th>回答人</th>
                        <th>提问时间</th>
                        <th>回答时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($faqs->count() > 0)
                        @foreach($faqs as $key=>$item)
                            <tr>
                                <td><b>{{$key+1}}</b></td>
                                <td>{{$item->ask_title}}</td>
                                <td>{{$item->getStatusOptions($item->status)}}</td>
                                <td>{{\App\Models\FAQCategory::getFAQCategoryPath($item->category_id)}}</td>
                                {{--<td>{{$item->answer}}</td>--}}
                                <td>{{$item->hasOneAsk!=null?$item->hasOneAsk->name:''}}</td>
                                <td>{{$item->hasOneAssign!=null?$item->hasOneAssign->name:''}}</td>
                                <td>{{$item->hasOneAnswer!=null?$item->hasOneAnswer->name:''}}</td>
                                <td>{{date("Y-m-d H:i:s",$item->ask_at)}}</td>
                                <td>{{date("Y-m-d H:i:s",$item->answer_at)}}</td>
                                <td class="operation">
                                    <a class="btn btn-sm btn-primary" href="{{ route('faq.edit',[$item->id]) }}" title="更新"><i class="fa fa-edit"></i> 更新</a>
                                    <form action="" method="POST" style="display: inline">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <a class="btn btn-sm btn-danger btn-delete" href="{{ route('faq.destroy',[$item->id]) }}" title="删除"><i class="fa fa-trash"></i> 删除</a>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                <div class="clearfix">
                    {{$faqs->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset("AdminLTE/bower_components/select2/dist/js/select2.full.min.js")}}"></script>
    <script src="{{asset("AdminLTE/bower_components/moment/min/moment.min.js")}}"></script>
    <script src="{{asset("AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.js")}}"></script>
    <script>
        $(function () {
            $('.select2').select2();

            $('#ask-time-range,#answer-time-range').daterangepicker({
                startDate:moment().subtract('days', 29),
                endDate:moment(),
                maxDate:moment(),
                locale:{
                    format: 'YYYY-MM-DD',
                    applyLabel: '确认',
                    cancelLabel: '取消',
                    separator:'~',
                    daysOfWeek:["日","一","二","三","四","五","六"],
                    monthNames: ["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
                }
            });

            @if(request()->ask_time_range)
                $('#ask-time-range').val('{{request()->ask_time_range}}');
            @else
                $('#ask-time-range').val('');
            @endif

            @if(request()->answer_time_range)
                $('#answer-time-range').val('{{request()->answer_time_range}}');
            @else
                $('#answer-time-range').val('');
            @endif

            $(".btn-reset").on('click',function(){
                $(this).closest('form').find('input').val('');
                $("select[name=category_id],select[name=status],select[name=ask_user_id],select[name=answer_user_id]").val('').trigger('change.select2');
                $('#ask-time-range,#answer-time-range').val('');
            });

        });
    </script>
@endsection
