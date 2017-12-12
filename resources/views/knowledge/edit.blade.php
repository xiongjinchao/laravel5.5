@extends('layouts.admin-lte')

@section('css')
    <link rel="stylesheet" href="{{asset("AdminLTE/bower_components/select2/dist/css/select2.min.css")}}">
    <link rel="stylesheet" href="{{asset("AdminLTE/plugins/iCheck/all.css")}}">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="knowledge-edit">
                <div class="box">
                    <form action="{{ route('knowledge.update',['id'=>$knowledge->id]) }}" method="POST">
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
                                        <label>知识标题</label>
                                        <input class="form-control" name="title" placeholder="请输入知识标题" value="{{$knowledge->title}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>所属目录</label>
                                        <select class="form-control select2" name="category_id" style="width: 100%;">
                                            <option value="0">请选择</option>
                                            @foreach($knowledgeCategories as $key=>$item)
                                                <option {{$knowledge->category_id == $key?'selected':''}} value="{{$key}}">{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>所属国家</label>
                                        <select class="form-control select2" name="country_id" style="width: 100%;">
                                            <option value="0">请选择</option>
                                            @foreach($countries as $item)
                                                <option {{$item['audit'] == 1?'disabled="disabled"':''}} {{$knowledge->country_id == $item['id']?'selected':''}} value="{{$item['id']}}">{{$item['country'].' '.$item['country_name_en'].''}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>内容</label>
                                        <textarea id="knowledge-content" name="content" rows="3" placeholder="请输入知识内容" style="height:300px;">{{$knowledge->content}}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>状态</label>
                                        <p class="knowledge-status">
                                            @foreach($knowledge->getStatusOptions() as $key => $item)
                                                <input type="radio" name="status" value="{{$key}}" {{$knowledge->status == $key?'checked':'disabled'}}>&nbsp;&nbsp;{{$item}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            @endforeach
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="box-footer">
                            <a href="{{ route('knowledge.log',['id'=>$knowledge->id]) }}" class="btn btn-default pull-right"><i class="fa fa-calendar"></i> 操作日志</a>

                            @if(in_array($knowledge->status,[\App\Models\Knowledge::STATUS_NEW,\App\Models\Knowledge::STATUS_FAIL_AUDIT,\App\Models\Knowledge::STATUS_OFFLINE]) == \App\Models\Knowledge::STATUS_NEW)
                                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> 保存知识</button>
                            @endif
                            @if($knowledge->status == \App\Models\Knowledge::STATUS_NEW || $knowledge->status == \App\Models\Knowledge::STATUS_FAIL_AUDIT || $knowledge->status == \App\Models\Knowledge::STATUS_OFFLINE)
                                <a href="{{ route('knowledge.submit',['id'=>$knowledge->id]) }}" class="btn btn-default"><i class="fa fa-coffee"></i> 提交审核</a>
                            @elseif($knowledge->status == \App\Models\Knowledge::STATUS_WAIT_AUDIT)
                                <a href="{{ route('knowledge.audit',['id'=>$knowledge->id,'status'=>\App\Models\Knowledge::STATUS_WAIT_PUBLISH]) }}" class="btn btn-success"><i class="fa fa-check"></i> 审核成功</a>
                                <a href="{{ route('knowledge.audit',['id'=>$knowledge->id,'status'=>\App\Models\Knowledge::STATUS_FAIL_AUDIT]) }}" class="btn btn-default"><i class="fa fa-close"></i> 审核失败</a>
                            @elseif($knowledge->status == \App\Models\Knowledge::STATUS_WAIT_PUBLISH)
                                <a href="{{ route('knowledge.publish',['id'=>$knowledge->id,'status'=>\App\Models\Knowledge::STATUS_ONLINE]) }}" class="btn btn-success"><i class="fa fa-cloud-upload"></i> 设为上线</a>
                                <a href="{{ route('knowledge.publish',['id'=>$knowledge->id,'status'=>\App\Models\Knowledge::STATUS_OFFLINE]) }}" class="btn btn-default"><i class="fa fa-cloud-download"></i> 设为下线</a>
                            @endif
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

            var ue = UE.getEditor('knowledge-content',{
                autoHeightEnabled: false,
                enableAutoSave:false,
                //serverUrl: '/server/ueditor/controller.php'
            });

            $('.knowledge-status input[type="radio"]').iCheck({
                radioClass:'iradio_square-blue'
            })
        })
    </script>
@endsection
