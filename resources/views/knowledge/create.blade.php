@extends('layouts.admin-lte')

@section('css')
    <link rel="stylesheet" href="{{asset("AdminLTE/bower_components/select2/dist/css/select2.min.css")}}">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="knowledge-edit">
                <div class="box">
                    <form action="{{ route('knowledge.store') }}" method="POST">
                        {{ csrf_field() }}
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
                                        <input class="form-control" name="title" placeholder="请输入知识标题">
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
                                                <option value="{{$key}}">{{$item}}</option>
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
                                                <option {{$item['audit'] == 1?'disabled="disabled"':''}} value="{{$item['id']}}">{{$item['country'].' '.$item['country_name_en'].''}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>内容</label>
                                        <textarea id="knowledge-content" name="content" rows="3" placeholder="请输入知识内容" style="height:300px;"></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> 保存知识</button>
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
    <script>
        $(function () {
            $('.select2').select2();

            var ue = UE.getEditor('knowledge-content',{
                autoHeightEnabled: false,
                enableAutoSave:false,
                serverUrl: '{{ route('upload') }}'
            });
            ue.ready(function() {
                ue.execCommand('serverparam','_token','{{ csrf_token() }}');
            });
        })
    </script>
@endsection
