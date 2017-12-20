@extends('layouts.admin-lte')


@section('css')
    <link rel="stylesheet" href="{{asset("AdminLTE/bower_components/select2/dist/css/select2.min.css")}}">
    <link rel="stylesheet" href="{{asset("AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.css")}}">
    <link href="{{asset("AdminLTE/bower_components/bootstrap-fileinput/css/fileinput.min.css")}}" media="all" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="notice-index">
        <div class="box search">
            <div class="box-header with-border">
                <h3 class="box-title">{{$page['title'] or ''}}</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <form action="{{ route('notice.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>公告编号</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-key"></i>
                                    </div>
                                    <input class="form-control" name="id" value="{{request('id')}}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>公告名称</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-header"></i>
                                    </div>
                                    <input class="form-control" name="title" value="{{request('title')}}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>公告状态</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-th-list"></i>
                                    </div>
                                    <select class="form-control select2" name="status" style="width: 100%;">
                                        <option value="">请选择</option>
                                        @foreach(\App\Models\Notice::getStatusOptions() as $key => $item)
                                            <option value="{{$key}}" {{request('status') == $key?'selected':''}}>{{$item}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        @if(!empty($organizations))
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>创建人</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <select class="form-control select2" name="author" style="width: 100%;">
                                        <option value="">请选择</option>
                                        @foreach($organizations as $item)
                                            <option disabled="disabled">{{$item->getSpace().$item->name}}</option>
                                            @if($item->hasManyUsers!=null)
                                                @foreach($item->hasManyUsers as $user)
                                                    <option value="{{$user->id}}" {{request('author') == $user->id?'selected':''}}>{{'┃ '.str_replace('┗ ','┣ ',$item->getSpace()).$user->name}}</option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>创建时间</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" name="created_time_range" class="form-control pull-right" id="created-time-range">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>&nbsp;</label><br/>
                                <button class="btn btn-primary btn-search"><i class="fa fa-search"></i> 搜索公告</button>
                                <a class="btn btn-warning btn-reset" href="javascript:void(0)"><i class="fa fa-remove"></i> 清空条件</a>
                                <a class="btn btn-success" href="{{route("notice.create")}}"><i class="fa fa-plus-circle"></i> 创建公告</a>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{$page['title'] or ''}}</h3>
            </div>
            <div class="box-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>标题</th>
                        <th>状态</th>
                        <th>操作人</th>
                        <th>是否有附件</th>
                        <th>创建时间</th>
                        <th>更新时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($notices->count() > 0)
                        @foreach($notices as $key=>$item)
                            <tr>
                                <td><b>{{$key+1}}</b></td>
                                <td>{{$item->title}}</td>
                                <td>{{\App\Models\Notice::getStatusOptions($item->status)}}</td>
                                <td>{{$item->hasOneUser!=null?$item->hasOneUser->name:''}}</td>
                                <td>{{count(\App\Models\ModelUpload::getUploads(['model'=>'Notice','model_id'=>$item->id]))>0?'有附件':'无附件'}}</td>
                                <td>{{$item->created_at}}</td>
                                <td>{{$item->updated_at}}</td>
                                <td class="operation">
                                    <a class="btn btn-sm btn-primary" href="{{ route('notice.edit',[$item->id]) }}" title="更新"><i class="fa fa-edit"></i> 更新</a>
                                    <form action="" method="POST" style="display: inline">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <a class="btn btn-sm btn-danger btn-delete" href="{{ route('notice.destroy',[$item->id]) }}" title="删除"><i class="fa fa-trash"></i> 删除</a>
                                    </form>

                                    @if($item->status == \App\Models\Notice::STATUS_PUBLISHED)
                                        <a class="btn btn-sm btn-success btn-change" href="{{ route('notice.change',[$item->id]) }}" title="转为知识"><i class="fa fa-refresh"></i> 转为知识</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                <div class="clearfix">
                    {{$notices->links()}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-change">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="" method="POST">
                    {{ csrf_field() }}
                    <div class="modal-header bg-blue">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-refresh"></i> 转换为知识</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> 放弃</button>
                        <button type="button" class="btn btn-primary pull-right confirm-change"><i class="fa fa-check"></i> 确认</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset("AdminLTE/bower_components/select2/dist/js/select2.full.min.js")}}"></script>
    <script src="{{asset("AdminLTE/bower_components/moment/min/moment.min.js")}}"></script>
    <script src="{{asset("AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.js")}}"></script>

    <script src="{{asset("AdminLTE/bower_components/ueditor/ueditor.config.js")}}"></script>
    <script src="{{asset("AdminLTE/bower_components/ueditor/ueditor.all.min.js")}}"></script>

    <script src="{{asset("AdminLTE/bower_components/bootstrap-fileinput/js/plugins/piexif.min.js")}}" type="text/javascript"></script>
    <script src="{{asset("AdminLTE/bower_components/bootstrap-fileinput/js/plugins/sortable.min.js")}}" type="text/javascript"></script>
    <script src="{{asset("AdminLTE/bower_components/bootstrap-fileinput/js/plugins/purify.min.js")}}" type="text/javascript"></script>
    <script src="{{asset("AdminLTE/bower_components/bootstrap-fileinput/js/fileinput.js")}}"></script>
    <script src="{{asset("AdminLTE/bower_components/bootstrap-fileinput/js/locales/zh.js")}}"></script>
    <script>
        $(function () {
            $('.select2').select2();

            $('#created-time-range').daterangepicker({
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

            @if(request('created_time_range'))
                $('#created-time-range').val('{{request('created_time_range')}}');
            @else
                $('#created-time-range').val('');
            @endif

            $(".btn-reset").on('click',function(){
                $(this).closest('form').find('input').val('');
                $("select[name=author],select[name=status]").val('').trigger('change.select2');
                $('#created-time-range').val('');
            });

            //转换为知识
            $(".btn-change").on('click',function(e){
                e.preventDefault();
                var action = $(this).attr('href');
                $("#modal-change form").attr('action',action);
                $("#modal-change").modal();
            });

            //转换为知识
            $("#modal-change").on('show.bs.modal',function(){
                $.ajax({
                    type: "GET",
                    url: $("#modal-change form").attr('action'),
                    data: {},
                    dataType: "json",
                    beforeSend: function(){
                        $("#modal-change .modal-body").html('<div class="overlay text-center"><img src="{{asset('images/loading.gif')}}" width="80"></div>');
                    },
                    success: function(data){
                        if(data.status == 'success') {
                            $("#modal-change .modal-body").html(data.html);

                            $('#modal-change .select2').select2();

                            var ue = UE.getEditor('knowledge-content',{
                                autoHeightEnabled: false,
                                enableAutoSave:false,
                                serverUrl: '{{ route('upload') }}'
                            });
                            ue.ready(function() {
                                ue.execCommand('serverparam','_token','{{ csrf_token() }}');
                            });

                            var preview = JSON.parse($("#preview").val());
                            var config = JSON.parse($("#config").val());

                            $("#input-file").fileinput({
                                uploadUrl: '{{ route('upload') }}?action=uploadfile&use_database=1',
                                language: 'zh',
                                initialCaption: '请选择附件',
                                maxFileSize: 2048,
                                showRemove:false,
                                showUpload:false,
                                uploadAsync: false,
                                minFileCount: 1,
                                maxFileCount: 5,
                                overwriteInitial: false,
                                initialPreview: preview,
                                initialPreviewAsData: true,
                                initialPreviewFileType: 'image', // image is the default and can be overridden in config below
                                initialPreviewConfig: config,
                                preferIconicPreview: true, // this will force thumbnails to display icons for following file extensions
                                previewFileIcon: '<i class="glyphicon glyphicon-file"></i>',
                                previewFileIconSettings: { // configure your icon file extensions
                                    'doc': '<i class="fa fa-file-word-o text-primary"></i>',
                                    'xls': '<i class="fa fa-file-excel-o text-success"></i>',
                                    'ppt': '<i class="fa fa-file-powerpoint-o text-danger"></i>',
                                    'pdf': '<i class="fa fa-file-pdf-o text-danger"></i>',
                                    'zip': '<i class="fa fa-file-archive-o text-muted"></i>',
                                    'htm': '<i class="fa fa-file-code-o text-info"></i>',
                                    'txt': '<i class="fa fa-file-text-o text-info"></i>',
                                    'mov': '<i class="fa fa-file-movie-o text-warning"></i>',
                                    'mp3': '<i class="fa fa-file-audio-o text-warning"></i>',
                                    // note for these file types below no extension determination logic
                                    // has been configured (the keys itself will be used as extensions)
                                    'jpg': '<i class="fa fa-file-photo-o text-danger"></i>',
                                    'gif': '<i class="fa fa-file-photo-o text-muted"></i>',
                                    'png': '<i class="fa fa-file-photo-o text-primary"></i>'
                                },
                                previewFileExtSettings: { // configure the logic for determining icon file extensions
                                    'doc': function(ext) {
                                        return ext.match(/(doc|docx)$/i);
                                    },
                                    'xls': function(ext) {
                                        return ext.match(/(xls|xlsx)$/i);
                                    },
                                    'ppt': function(ext) {
                                        return ext.match(/(ppt|pptx)$/i);
                                    },
                                    'zip': function(ext) {
                                        return ext.match(/(zip|rar|tar|gzip|gz|7z)$/i);
                                    },
                                    'htm': function(ext) {
                                        return ext.match(/(htm|html)$/i);
                                    },
                                    'txt': function(ext) {
                                        return ext.match(/(txt|ini|csv|java|php|js|css)$/i);
                                    },
                                    'mov': function(ext) {
                                        return ext.match(/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i);
                                    },
                                    'mp3': function(ext) {
                                        return ext.match(/(mp3|wav)$/i);
                                    }
                                },
                                uploadExtraData: { _token:'{{ csrf_token() }}'},
                                deleteExtraData: { _token:'{{ csrf_token() }}'}
                            }).on('filesorted', function(e, params) {

                                var upload_id = [];
                                $.each(params.stack,function(i,item){
                                    upload_id.push(item.key);
                                });
                                $("input[name=upload_id]").val(upload_id.join(','));

                            }).on('fileuploaded', function(event, data, previewId, index) {
                                if(data.response.state == 'SUCCESS') {
                                    $("#" + previewId).find(".kv-file-remove:visible").attr({
                                        'data-key': data.response.id,
                                        'data-url': 'http://{{request()->server('HTTP_HOST')}}/delete/' + data.response.id
                                    });
                                    var upload_id = [];
                                    $(".file-drop-zone .kv-file-remove:visible").each(function (i, item) {
                                        if ($(item).attr('data-key') > 0) {
                                            upload_id.push($(item).attr('data-key'));
                                        }
                                    });
                                    $("input[name=upload_id]").val(upload_id.join(','));
                                }else{
                                    alert(data.response.state);
                                    $("#"+previewId).remove();
                                }

                            }).on('filesuccessremove', function(event, id) {

                                var upload_id = $("input[name=upload_id]").val().split(',');
                                upload_id.splice($.inArray($("#"+id).find(".kv-file-remove:visible").attr('data-key'), upload_id), 1);
                                $("input[name=upload_id]").val(upload_id.join(','));

                            }).on('filedeleted', function(event, key, jqXHR, data) {

                                var upload_id = $("input[name=upload_id]").val().split(',');
                                upload_id.splice($.inArray(key.toString(), upload_id), 1);
                                $("input[name=upload_id]").val(upload_id.join(','));

                            });
                        }else{
                            $("#modal-change .modal-body").html('<div class="callout callout-danger"><h4>操作提醒</h4>'+data.message+'</div>');
                        }
                    },
                    error: function(data){
                        $("#modal-change .modal-body").html('<div class="callout callout-danger"><h4>操作提醒</h4>'+data.responseJSON.message+'</div>');
                    }
                });
            });

            $("#modal-change .confirm-change").on('click',function(){
                $(this).closest('form').submit();
            });

        });
    </script>
@endsection
