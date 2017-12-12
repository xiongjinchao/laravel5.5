@extends('layouts.admin-lte')

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">操作日志</h3>
                </div>
                <div class="box-body">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th width="70%">日志内容</th>
                            <th>操作人</th>
                            <th>操作时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($logs->count() > 0)
                            @foreach($logs as $key=>$item)
                                <tr>
                                    <td><b>{{$key+1}}</b></td>
                                    <td>{{$item->content}}</td>
                                    <td>{{$item->hasOneUser!=null?$item->hasOneUser->name:''}}</td>
                                    <td>{{$item->created_at}}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

