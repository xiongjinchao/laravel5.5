<?php
$page = [
    'title' => '(#403)ERROR',
    'subTitle' => '',
    'breadcrumb' => [
        ['url' => '#','label' => '(#403)ERROR' ]
    ]
];
?>
@extends('layouts.admin-lte')

@section('content')
    <div class="error-page" style="margin-top:200px;">
        <h2 class="headline text-red">403</h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-red"></i> 抱歉，你没有权限访问.</h3>
            <p>
                请联系百程知识库管理员，
                或者 <a href=" {{route('home')}} ">返回</a>.
            </p>
            <form class="search-form" action="http://www.baicheng.com">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="http://www.baicheng.com">
                    <div class="input-group-btn">
                        <button type="submit" name="submit" class="btn btn-danger btn-flat"><i class="fa fa-search"></i></button>
                    </div>
                </div><!-- /.input-group -->
            </form>
        </div>
    </div><!-- /.error-page -->
@endsection