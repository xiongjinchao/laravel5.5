@extends('layouts.admin-lte')

@section('css')
    <link rel="stylesheet" href="{{asset("AdminLTE/bower_components/select2/dist/css/select2.min.css")}}">
    <link rel="stylesheet" href="{{asset("AdminLTE/plugins/iCheck/all.css")}}">
    <link href="{{asset("AdminLTE/bower_components/bootstrap-fileinput/css/fileinput.min.css")}}" media="all" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="notice-edit">
                <div class="box">
                    <form action="{{ route('notice.update',['id'=>$notice->id]) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="box-header with-border">
                            <h3 class="box-title">{{$page['title'] or ''}}</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>公告标题</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-header"></i>
                                            </div>
                                            <input class="form-control" name="title" value="{{ old('title',$notice->title) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>公告内容</label>
                                        <textarea id="notice-content" name="content" rows="3" placeholder="请输入公告内容" style="height:300px;">{{ old('content',$notice->content) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>
                                            上传附件
                                            <input type="hidden" name="upload_id" value="{{$upload_id}}">
                                        </label>
                                        <div class="file-loading">
                                            <input id="input-file" name="upfile" type="file" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>公告状态</label>
                                        <p class="notice-status">
                                            @foreach(\App\Models\Notice::getStatusOptions() as $key => $item)
                                                <input type="radio" name="status" value="{{$key}}" {{$notice->status == $key?'checked':'disabled'}}>&nbsp;&nbsp;{{$item}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            @endforeach
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>创建人</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <input class="form-control" value="{{$notice->hasOneAuthor!=null?$notice->hasOneAuthor->name:''}}" readonly>
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
                                            <input class="form-control" value="{{$notice->hasOneUser!=null?$notice->hasOneUser->name:''}}" readonly>
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
                                            <input class="form-control" value="{{$notice->created_at}}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(strtotime($notice->updated_at) > 0)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>更新时间</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input class="form-control" value="{{$notice->updated_at}}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif


                        </div>

                        <div class="box-footer">
                            <a href="{{ route('notice.log',['id'=>$notice->id]) }}" class="btn btn-default pull-right"><i class="fa fa-calendar"></i> 操作日志</a>
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

    <script src="{{asset("AdminLTE/bower_components/bootstrap-fileinput/js/plugins/piexif.min.js")}}" type="text/javascript"></script>
    <script src="{{asset("AdminLTE/bower_components/bootstrap-fileinput/js/plugins/sortable.min.js")}}" type="text/javascript"></script>
    <script src="{{asset("AdminLTE/bower_components/bootstrap-fileinput/js/plugins/purify.min.js")}}" type="text/javascript"></script>
    <script src="{{asset("AdminLTE/bower_components/bootstrap-fileinput/js/fileinput.js")}}"></script>
    <script src="{{asset("AdminLTE/bower_components/bootstrap-fileinput/js/locales/zh.js")}}"></script>
    <script>
        $(function () {
            $('.select2').select2();
            $('.select2[name=ask_user_id],.select2[name=operator]').select2({"disabled":true});

            var ue = UE.getEditor('notice-content',{
                autoHeightEnabled: false,
                enableAutoSave:false,
                serverUrl: '{{ route('upload') }}'
            });
            ue.ready(function() {
                ue.execCommand('serverparam','_token','{{ csrf_token() }}');
            });

            $('.notice-status input[type="radio"]').iCheck({
                radioClass:'iradio_square-blue'
            });

            var preview = JSON.parse('{!!$preview!!}');
            var config = JSON.parse('{!!$config!!}');
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
                initialPreviewConfig:  config,
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
        })
    </script>
@endsection
