@extends('layouts.admin-lte')

@section('css')
    <link rel="stylesheet" href="{{asset("AdminLTE/bower_components/select2/dist/css/select2.min.css")}}">
    <link rel="stylesheet" href="{{asset("AdminLTE/plugins/iCheck/all.css")}}">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="faq-edit">
                <div class="box">
                    <form action="{{ route('faq.update',['id'=>$faq->id]) }}" method="POST">
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
                                        <label>问题标题</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-header"></i>
                                            </div>
                                            <input class="form-control" name="title" value="{{$faq->title}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>回答</label>
                                        <textarea id="faq-answer" name="answer" rows="3" placeholder="请输入回答内容" style="height:300px;">{{$faq->answer}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>所属分类</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-sitemap"></i>
                                            </div>
                                            <select class="form-control select2" name="category_id" style="width: 100%;">
                                                <option value="0">请选择</option>
                                                @foreach(\App\Models\FAQCategory::getFAQCategoryOptions() as $key=>$item)
                                                    <option {{$faq->category_id == $key?'selected':''}} value="{{$key}}">{{$item}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>问题状态</label>
                                        <p class="faq-status">
                                            @foreach(\App\Models\FAQ::getStatusOptions() as $key => $item)
                                                <input type="radio" name="status" value="{{$key}}" {{$faq->status == $key?'checked':'disabled'}}>&nbsp;&nbsp;{{$item}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            @endforeach
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>提问人</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <select class="form-control select2" name="ask_user_id" style="width: 100%;">
                                                <option value="">请选择</option>
                                                @if(!empty($organizations))
                                                    @foreach($organizations as $item)
                                                        <option disabled="disabled">{{$item->getSpace().$item->name}}</option>
                                                        @if($item->hasManyUsers!=null)
                                                            @foreach($item->hasManyUsers as $user)
                                                                <option value="{{$user->id}}" {{$faq->ask_user_id == $user->id?'selected':''}}>{{'┃ '.str_replace('┗ ','┣ ',$item->getSpace()).$user->name}}</option>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>指派人</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <select class="form-control select2" name="assign_user_id" style="width: 100%;">
                                                <option value="">请选择</option>
                                                @if(!empty($organizations))
                                                    @foreach($organizations as $item)
                                                        <option disabled="disabled">{{$item->getSpace().$item->name}}</option>
                                                        @if($item->hasManyUsers!=null)
                                                            @foreach($item->hasManyUsers as $user)
                                                                <option value="{{$user->id}}" {{$faq->assign_user_id == $user->id?'selected':''}}>{{'┃ '.str_replace('┗ ','┣ ',$item->getSpace()).$user->name}}</option>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>回答人</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <select class="form-control select2" name="assign_user_id" style="width: 100%;">
                                                <option value="">请选择</option>
                                                @if(!empty($organizations))
                                                    @foreach($organizations as $item)
                                                        <option disabled="disabled">{{$item->getSpace().$item->name}}</option>
                                                        @if($item->hasManyUsers!=null)
                                                            @foreach($item->hasManyUsers as $user)
                                                                <option value="{{$user->id}}" {{$faq->answer_user_id == $user->id?'selected':''}}>{{'┃ '.str_replace('┗ ','┣ ',$item->getSpace()).$user->name}}</option>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
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
                                            <input class="form-control" value="{{$faq->hasOneUser!=null?$faq->hasOneUser->name:''}}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>提问时间</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input class="form-control" value="{{date('Y-m-d H:i:s',$faq->ask_at)}}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($faq->answer_at != '')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>回答时间</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input class="form-control" value="{{date('Y-m-d H:i:s',$faq->answer_at)}}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif


                        </div>

                        <div class="box-footer">
                            <a href="{{ route('faq.log',['id'=>$faq->id]) }}" class="btn btn-default pull-right"><i class="fa fa-calendar"></i> 操作日志</a>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> 保存</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset("AdminLTE/bower_components/select2/dist/js/select2.full.min.js")}}"></script>
    <script src="{{asset("AdminLTE/bower_components/ueditor/ueditor.config.js")}}"></script>
    <script src="{{asset("AdminLTE/bower_components/ueditor/ueditor.all.min.js")}}"></script>
    <script src="{{asset("AdminLTE/plugins/iCheck/icheck.min.js")}}"></script>
    <script>
        $(function () {
            $('.select2').select2();
            $('.select2[name=ask_user_id],.select2[name=operator]').select2({"disabled":true});

            var ue = UE.getEditor('faq-answer',{
                autoHeightEnabled: false,
                enableAutoSave:false,
                serverUrl: '{{ route('upload') }}'
            });
            ue.ready(function() {
                ue.execCommand('serverparam','_token','{{ csrf_token() }}');
            });

            $('.faq-status input[type="radio"]').iCheck({
                radioClass:'iradio_square-blue'
            });
        })
    </script>
@endsection
