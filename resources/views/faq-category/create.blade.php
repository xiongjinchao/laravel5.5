@extends('layouts.admin-lte')

@section('css')
    <link rel="stylesheet" href="{{asset("AdminLTE/bower_components/select2/dist/css/select2.min.css")}}">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="faq-category-create">
                <div class="box">
                    <form action="{{ route('faq-category.store') }}" method="POST">
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
                                        <label>分类名称</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-header"></i>
                                            </div>
                                            <input class="form-control" name="name" value="{{ old('name') }}">
                                        </div>
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
                                            <select class="form-control select2" name="parent" style="width: 100%;">
                                                <option value="">设为主分类</option>
                                                @foreach($faqCategories as $key=>$item)
                                                    <option {{old('parent') == $key?'selected':''}} value="{{$key}}">{{$item}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
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
    <script>
        $(function () {
            $('.select2').select2();
        })
    </script>
@endsection
